<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $dados = $request->validated();

        $senha = mb_strtolower($dados['password']);
        $cpf = (string) $dados['cpf'];
        $emailLocal = mb_strtolower(explode('@', (string) $dados['email'])[0]);

        $partesNome = preg_split('/\s+/', mb_strtolower((string) $dados['nome_completo'])) ?: [];
        $tokensNome = array_values(array_filter($partesNome, fn ($parte) => mb_strlen($parte) >= 3));

        foreach ($tokensNome as $tokenNome) {
            if (str_contains($senha, $tokenNome)) {
                return back()
                    ->withErrors(['password' => 'A senha nao pode conter partes do seu nome.'])
                    ->withInput();
            }
        }

        if (str_contains($senha, $cpf)) {
            return back()
                ->withErrors(['password' => 'A senha nao pode conter o CPF.'])
                ->withInput();
        }

        if ($emailLocal !== '' && str_contains($senha, $emailLocal)) {
            return back()
                ->withErrors(['password' => 'A senha nao pode conter o e-mail.'])
                ->withInput();
        }

        // Criar usuário
        User::create([
            'nome_completo' => $dados['nome_completo'],
            'cpf' => $dados['cpf'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'] ?? null,
            'password' => $dados['password'],
            'cep' => $dados['cep'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'],
            'bairro' => $dados['bairro'],
            'municipio' => $dados['municipio'],
            'estado' => $dados['estado'],
        ]);

        return redirect('/');
    }




    public function login(Request $request)
    {
        // validação básica
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // tentativa de login
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
            
            Auth::user()->update([
                'ultimo_acesso' => now()
            ]);

            return redirect('/PaginaInicial');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas',
        ]);
    }


    protected function redirectTo($request)
    {
        return '/';
    }
}
