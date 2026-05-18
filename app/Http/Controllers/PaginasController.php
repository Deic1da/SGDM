<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaginasController extends Controller
{
    public function inicio() {
        $farmaceuticoId = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->value('id');

        $temAcessoFarmaceutico = (bool) $farmaceuticoId;
        $entidadesGerenciaveis = collect();

        if ($farmaceuticoId) {
            $entidadesGerenciaveis = DB::table('entidades as e')
                ->join('vinculos_farmaceutico as vf', 'vf.id_entidade', '=', 'e.id')
                ->where('vf.id_farmaceutico', $farmaceuticoId)
                ->where('vf.status_vinculo', 'Aceito')
                ->where('e.status', '!=', 'Inativo')
                ->select('e.id', 'e.nome_fantasia', 'e.razao_social')
                ->orderBy('e.nome_fantasia')
                ->get();
        }

        return view('PaginaInicial', [
            'temAcessoFarmaceutico' => $temAcessoFarmaceutico,
            'entidadesGerenciaveis' => $entidadesGerenciaveis,
        ]);
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
