<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicamentoDoacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_medicamento' => ['required', 'string', 'max:255'],
            'forma_farmaceutica' => ['required', 'string', 'in:Comprimido,Capsula,Cápsula,Xarope'],
            'condicao_embalagem' => ['required', 'string', 'max:100'],
            'data_validade' => ['required', 'date', 'after_or_equal:today'],
            'lacrado' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'lacrado' => $this->boolean('lacrado'),
        ]);
    }
}
