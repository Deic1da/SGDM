<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaginasController extends Controller
{
    public function inicio(Request $request) {
        $termoMedicamento = trim((string) $request->query('medicamento', ''));
        $farmaceuticoId = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->value('id');

        $temAcessoFarmaceutico = (bool) $farmaceuticoId;
        $entidadesGerenciaveis = collect();

        if ($termoMedicamento !== '') {
            $pontosColeta = DB::table('entidades as e')
                ->join('estoque_entidade as ee', 'ee.id_entidade', '=', 'e.id')
                ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
                ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
                ->where('e.status', 'Aprovado')
                ->where('ee.status_estoque', 'Disponivel')
                ->where('ee.quantidade', '>', 0)
                ->where('v.status_validacao', 'Aprovado')
                ->where('md.status_doacao', 'Aprovado')
                ->where('md.nome_medicamento', 'like', '%' . $termoMedicamento . '%')
                ->select(
                    'e.id',
                    'e.nome_fantasia',
                    'e.razao_social',
                    'e.logradouro',
                    'e.numero',
                    'e.bairro',
                    'e.municipio',
                    'e.estado',
                    'e.horario_funcionamento',
                    'e.latitude',
                    'e.longitude'
                )
                ->selectRaw('MIN(md.nome_medicamento) as medicamento_encontrado')
                ->selectRaw('SUM(ee.quantidade) as quantidade_disponivel')
                ->groupBy(
                    'e.id',
                    'e.nome_fantasia',
                    'e.razao_social',
                    'e.logradouro',
                    'e.numero',
                    'e.bairro',
                    'e.municipio',
                    'e.estado',
                    'e.horario_funcionamento',
                    'e.latitude',
                    'e.longitude'
                )
                ->orderBy('e.nome_fantasia')
                ->orderBy('e.razao_social')
                ->get();
        } else {
            $pontosColeta = DB::table('entidades')
                ->where('status', 'Aprovado')
                ->select(
                    'id',
                    'nome_fantasia',
                    'razao_social',
                    'logradouro',
                    'numero',
                    'bairro',
                    'municipio',
                    'estado',
                    'horario_funcionamento',
                    'latitude',
                    'longitude'
                )
                ->orderBy('nome_fantasia')
                ->orderBy('razao_social')
                ->get();
        }

        if ($farmaceuticoId) {
            $entidadesGerenciaveis = DB::table('entidades as e')
                ->join('vinculos_farmaceutico as vf', 'vf.id_entidade', '=', 'e.id')
                ->where('vf.id_farmaceutico', $farmaceuticoId)
                ->where('vf.status_vinculo', 'Aceito')
                ->where('e.status', 'Aprovado')
                ->select('e.id', 'e.nome_fantasia', 'e.razao_social')
                ->orderBy('e.nome_fantasia')
                ->get();
        }

        return view('PaginaInicial', [
            'temAcessoFarmaceutico' => $temAcessoFarmaceutico,
            'entidadesGerenciaveis' => $entidadesGerenciaveis,
            'pontosColeta' => $pontosColeta,
            'termoMedicamento' => $termoMedicamento,
        ]);
    }

    public function sugestoesMedicamentos(Request $request)
    {
        $termoMedicamento = trim((string) $request->query('termo', ''));

        if (mb_strlen($termoMedicamento) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'medicamentos_sugestoes:' . md5(mb_strtolower($termoMedicamento));

        $sugestoes = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($termoMedicamento) {
            return DB::table('estoque_entidade as ee')
                ->join('entidades as e', 'e.id', '=', 'ee.id_entidade')
                ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
                ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
                ->where('e.status', 'Aprovado')
                ->where('ee.status_estoque', 'Disponivel')
                ->where('ee.quantidade', '>', 0)
                ->where('v.status_validacao', 'Aprovado')
                ->where('md.status_doacao', 'Aprovado')
                ->where('md.nome_medicamento', 'like', '%' . $termoMedicamento . '%')
                ->distinct()
                ->orderBy('md.nome_medicamento')
                ->limit(8)
                ->pluck('md.nome_medicamento')
                ->values();
        });

        return response()->json($sugestoes);
    }

    public function descarte() {
        return view('Descarte');
    }

    public function cadastroFarmaceutico() {
        return view('CadastroFarmaceutico');
    }

    public function cadastroMedicamento() {
        return view('CadastroMedicamento');
    }

    public function cadastroPontoColeta() {
        return view('CadastroPontoColeta');
    }

    public function validarDoacao(Entidade $entidade) {
        $farmaceuticoId = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->value('id');

        $temVinculo = DB::table('vinculos_farmaceutico')
            ->where('id_farmaceutico', $farmaceuticoId)
            ->where('id_entidade', $entidade->id)
            ->where('status_vinculo', 'Aceito')
            ->exists();

        abort_unless($temVinculo, 403);

        return view('ValidarDoacao', ['entidade' => $entidade]);
    }

    public function estoqueMedicamento() {
        return view('EstoqueMedicamento');
    }
}
