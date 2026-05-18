<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFarmaceuticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'num_crf' => ['required', 'string', 'max:20', 'unique:farmaceuticos,num_crf'],
            'uf_crf' => [
                'required',
                'string',
                'size:2',
                Rule::in([
                    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS',
                    'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC',
                    'SP', 'SE', 'TO',
                ]),
            ],
            'id_usuario_pf' => [
                Rule::unique('farmaceuticos', 'id_usuario_pf')->where(fn ($query) => $query->where('id_usuario_pf', auth()->id())),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'num_crf' => strtoupper(trim((string) $this->input('num_crf'))),
            'uf_crf' => strtoupper((string) $this->input('uf_crf')),
            'id_usuario_pf' => auth()->id(),
        ]);
    }

    public function messages(): array
    {
        return [
            'num_crf.unique' => 'Este CRF ja esta cadastrado.',
            'uf_crf.in' => 'Selecione uma UF valida para o CRF.',
            'id_usuario_pf.unique' => 'Este usuario ja possui cadastro de farmaceutico.',
        ];
    }
}
