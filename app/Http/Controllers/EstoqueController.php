<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Rules\ValidCpf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    public function index(Request $request, Entidade $entidade)
    {
        $this->farmaceuticoAutorizado($entidade);
        $termoMedicamento = trim((string) $request->query('medicamento', ''));

        $itens = DB::table('estoque_entidade as ee')
            ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
            ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
            ->where('ee.id_entidade', $entidade->id)
            ->whereIn('ee.status_estoque', ['Disponivel', 'Reservado'])
            ->where('ee.quantidade', '>', 0)
            ->when($termoMedicamento !== '', function ($query) use ($termoMedicamento) {
                $query->where('md.nome_medicamento', 'like', '%' . $termoMedicamento . '%');
            })
            ->select([
                'ee.id',
                'md.nome_medicamento',
                'md.forma_farmaceutica',
                'ee.quantidade',
                'md.data_validade',
                'ee.status_estoque',
            ])
            ->orderByDesc('ee.data_entrada')
            ->paginate(10)
            ->appends(['medicamento' => $termoMedicamento]);

        return view('EstoqueMedicamento', [
            'entidade' => $entidade,
            'itens' => $itens,
            'termoMedicamento' => $termoMedicamento,
        ]);
    }

    public function sugestoes(Request $request, Entidade $entidade)
    {
        $this->farmaceuticoAutorizado($entidade);
        $termoMedicamento = trim((string) $request->query('termo', ''));

        if (mb_strlen($termoMedicamento) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'estoque_sugestoes:' . $entidade->id . ':' . md5(mb_strtolower($termoMedicamento));

        $sugestoes = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($entidade, $termoMedicamento) {
            return DB::table('estoque_entidade as ee')
                ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
                ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
                ->where('ee.id_entidade', $entidade->id)
                ->whereIn('ee.status_estoque', ['Disponivel', 'Reservado'])
                ->where('ee.quantidade', '>', 0)
                ->where('md.nome_medicamento', 'like', '%' . $termoMedicamento . '%')
                ->distinct()
                ->orderBy('md.nome_medicamento')
                ->limit(8)
                ->pluck('md.nome_medicamento')
                ->values();
        });

        return response()->json($sugestoes);
    }

    public function registrarEntrega(Request $request, Entidade $entidade): RedirectResponse
    {
        $farmaceuticoId = $this->farmaceuticoAutorizado($entidade);

        $dados = $request->validate([
            'estoque_id' => ['required', 'integer'],
            'cpf' => ['required', 'string', new ValidCpf()],
        ], [
            'estoque_id.required' => 'Selecione um medicamento da lista antes de registrar a entrega.',
            'cpf.required' => 'Informe o CPF do destinatario.',
        ]);

        $cpf = preg_replace('/\D/', '', (string) $dados['cpf']);
        $receptor = DB::table('usuarios')
            ->where('cpf', $cpf)
            ->first();

        if (!$receptor) {
            return back()
                ->withInput()
                ->withErrors(['cpf' => 'CPF valido, mas nao encontrado no cadastro de usuarios.']);
        }

        $resultado = DB::transaction(function () use ($dados, $entidade, $farmaceuticoId, $receptor) {
            $itemEstoque = DB::table('estoque_entidade as ee')
                ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
                ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
                ->where('ee.id', $dados['estoque_id'])
                ->where('ee.id_entidade', $entidade->id)
                ->whereIn('ee.status_estoque', ['Disponivel', 'Reservado'])
                ->where('ee.quantidade', '>', 0)
                ->select('ee.id', 'ee.quantidade', 'ee.status_estoque', 'md.nome_medicamento')
                ->lockForUpdate()
                ->first();

            if (!$itemEstoque) {
                return [
                    'ok' => false,
                    'mensagem' => 'Selecione um medicamento disponivel ou reservado para registrar a entrega.',
                ];
            }

            DB::table('entregas')->insert([
                'id_estoque_entidade' => $itemEstoque->id,
                'id_farmaceutico_entregador' => $farmaceuticoId,
                'id_receptor_pf' => $receptor->id,
                'data_entrega' => now(),
                'status_entrega' => 'Realizada',
            ]);

            if ((int) $itemEstoque->quantidade > 1) {
                DB::table('estoque_entidade')
                    ->where('id', $itemEstoque->id)
                    ->decrement('quantidade');
            } else {
                DB::table('estoque_entidade')
                    ->where('id', $itemEstoque->id)
                    ->update([
                        'quantidade' => 0,
                        'status_estoque' => 'Entregue',
                    ]);
            }

            return [
                'ok' => true,
                'mensagem' => 'Entrega de ' . $itemEstoque->nome_medicamento . ' registrada com sucesso.',
            ];
        });

        if (!$resultado['ok']) {
            return back()->withInput()->with('error', $resultado['mensagem']);
        }

        return back()->with('success', $resultado['mensagem']);
    }

    private function farmaceuticoAutorizado(Entidade $entidade): int
    {
        $farmaceuticoId = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->value('id');

        $temVinculo = DB::table('vinculos_farmaceutico')
            ->where('id_farmaceutico', $farmaceuticoId)
            ->where('id_entidade', $entidade->id)
            ->where('status_vinculo', 'Aceito')
            ->exists();

        abort_unless($farmaceuticoId && $temVinculo, 403);

        return (int) $farmaceuticoId;
    }
}
