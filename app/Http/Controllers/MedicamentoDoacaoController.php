<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicamentoDoacaoRequest;
use App\Models\MedicamentoDoacao;
use Carbon\Carbon;

class MedicamentoDoacaoController extends Controller
{
    public function store(StoreMedicamentoDoacaoRequest $request)
    {
        $dados = $request->validated();

        $forma = mb_strtolower((string) $dados['forma_farmaceutica']);
        $condicao = mb_strtolower((string) $dados['condicao_embalagem']);
        $lacrado = (bool) ($dados['lacrado'] ?? false);

        $validadeMinima = Carbon::today()->addDays(30);
        $validadeInformada = Carbon::parse((string) $dados['data_validade']);

        if ($validadeInformada->lt($validadeMinima)) {
            return redirect()
                ->route('descarte')
                ->with('warning', 'Medicamento com validade menor que 30 dias deve ser direcionado ao descarte.');
        }

        if (in_array($forma, ['comprimido', 'capsula', 'cápsula'], true) && str_contains($condicao, 'cartela') && !str_contains($condicao, 'lacrad')) {
            return redirect()
                ->route('descarte')
                ->with('warning', 'Comprimidos em cartela devem estar lacrados para doacao.');
        }

        if ($forma === 'xarope' && !$lacrado && !str_contains($condicao, 'lacrad')) {
            return redirect()
                ->route('descarte')
                ->with('warning', 'Xarope deve estar lacrado para doacao.');
        }

        $condicaoEmbalagem = $dados['condicao_embalagem'];
        if ($lacrado && stripos($condicaoEmbalagem, 'lacrado') === false) {
            $condicaoEmbalagem = 'Lacrado - ' . $condicaoEmbalagem;
        }

        MedicamentoDoacao::create([
            'id_doador' => auth()->id(),
            'nome_medicamento' => $dados['nome_medicamento'],
            'forma_farmaceutica' => $dados['forma_farmaceutica'],
            'condicao_embalagem' => $condicaoEmbalagem,
            'data_validade' => $dados['data_validade'],
            'status_doacao' => 'Cadastrado',
            'id_entidade_destino' => null,
        ]);

        return back()->with('success', 'Medicamento cadastrado com sucesso.');
    }
}
