<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    public function index(Entidade $entidade)
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

        abort_unless($temVinculo, 403);

        $itens = DB::table('estoque_entidade as ee')
            ->join('validacoes as v', 'v.id', '=', 'ee.id_validacao')
            ->join('medicamentos_doacao as md', 'md.id', '=', 'v.id_medicamento_doacao')
            ->where('ee.id_entidade', $entidade->id)
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

        return view('EstoqueMedicamento', [
            'entidade' => $entidade,
            'itens' => $itens,
        ]);
    }
}
