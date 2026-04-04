<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validação
        $request->validate([
            'nome_completo' => 'required',
            'cpf' => 'required|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:8|confirmed',
        ]);

        // Criar usuário
        User::create([
            'nome_completo' => $request->nome_completo,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => $request->password,

            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'estado' => $request->estado,
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
