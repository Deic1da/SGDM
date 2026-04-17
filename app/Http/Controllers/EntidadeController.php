<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntidadeRequest;
use App\Models\Entidade;

class EntidadeController extends Controller
{
    public function store(StoreEntidadeRequest $request)
    {
        $dados = $request->validated();

        Entidade::create([
            'razao_social' => $dados['razao_social'],
            'nome_fantasia' => $dados['nome_fantasia'] ?? null,
            'cnpj' => $dados['cnpj'],
            'id_dono_entidade' => auth()->id(),
            'horario_funcionamento' => $dados['horario_funcionamento'],
            'aceita_validade_curta' => (bool) ($dados['aceita_validade_curta'] ?? false),
            'status' => 'Aprovado',
            'latitude' => 0,
            'longitude' => 0,
            'farmaceutico_rt' => null,
            'cep' => $dados['cep'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'],
            'bairro' => $dados['bairro'],
            'municipio' => $dados['municipio'],
            'estado' => $dados['estado'],
        ]);

        return redirect()
            ->route('cadastro-ponto-coleta')
            ->with('success', 'Ponto de coleta cadastrado com status Aprovado.');
    }
}
