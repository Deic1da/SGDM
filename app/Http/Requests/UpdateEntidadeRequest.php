<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntidadeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $entidade = $this->route('entidade');

        return [
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'size:14',
                Rule::unique('entidades', 'cnpj')->ignore($entidade?->id),
            ],
            'horario_funcionamento' => ['required', 'string', 'max:100'],
            'aceita_validade_curta' => ['nullable', 'boolean'],
            'cep' => ['required', 'string', 'size:8'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'size:2'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cnpj' => preg_replace('/\D/', '', (string) $this->input('cnpj')),
            'aceita_validade_curta' => $this->boolean('aceita_validade_curta'),
            'cep' => preg_replace('/\D/', '', (string) $this->input('cep')),
            'estado' => strtoupper((string) $this->input('estado')),
        ]);
    }

    public function messages(): array
    {
        return [
            'cnpj.size' => 'O CNPJ deve conter 14 digitos.',
            'cnpj.unique' => 'Este CNPJ ja esta cadastrado.',
            'cep.size' => 'O CEP deve conter 8 digitos.',
        ];
    }
}
