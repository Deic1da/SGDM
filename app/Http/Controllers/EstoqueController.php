<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    public function index()
    {
        $farmaceuticoId = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->value('id');

        $entidadeIds = DB::table('vinculos_farmaceutico')
            ->where('id_farmaceutico', $farmaceuticoId)
            ->where('status_vinculo', 'Aceito')
            ->pluck('id_entidade');

        $itens = DB::table('estoque_entidade as ee')
            ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
            ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
            ->whereIn('ee.id_entidade', $entidadeIds)
            ->select([
                'ee.id',
                'md.nome_medicamento',
                'md.forma_farmaceutica',
                'ee.quantidade',
                'md.data_validade',
                'ee.status_estoque',
            ])
            ->orderByDesc('ee.data_entrada')
            ->paginate(10);

        return view('EstoqueMedicamento', ['itens' => $itens]);
    }
}
