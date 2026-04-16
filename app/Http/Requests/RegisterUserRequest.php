<?php

namespace App\Http\Requests;

use App\Rules\ValidCpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_completo' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:usuarios,cpf', new ValidCpf()],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'telefone' => ['nullable', 'string', 'max:15'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^\S+$/',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'cep' => ['required', 'string', 'size:8'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'string', 'size:2'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => preg_replace('/\D/', '', (string) $this->input('cpf')),
            'telefone' => preg_replace('/\D/', '', (string) $this->input('telefone')),
            'cep' => preg_replace('/\D/', '', (string) $this->input('cep')),
            'estado' => strtoupper((string) $this->input('estado')),
        ]);
    }

    public function messages(): array
    {
        return [
            'cpf.size' => 'O CPF deve conter 11 digitos.',
            'cpf.unique' => 'Este CPF ja esta cadastrado.',
            'email.unique' => 'Este e-mail ja esta cadastrado.',
            'password.regex' => 'A senha nao pode conter espacos.',
            'password.min' => 'A senha deve ter no minimo 8 caracteres.',
            'cep.size' => 'O CEP deve conter 8 digitos.',
        ];
    }
}
