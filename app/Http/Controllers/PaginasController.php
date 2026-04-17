<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaginasController extends Controller
{
    public function inicio() {
        $temAcessoFarmaceutico = DB::table('farmaceuticos')
            ->where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->exists();

        return view('PaginaInicial', [
            'temAcessoFarmaceutico' => $temAcessoFarmaceutico,
        ]);
    }

    public function descarte() {
        return view('Descarte');
    }

    public function cadastroFarmaceutico() {
        return view('CadastroFarmaceutico');
    }

    public function cadastroMedicamento() {
        return view('CadastroMedicamento');
    }

    public function cadastroPontoColeta() {
        return view('CadastroPontoColeta');
    }

    public function validarDoacao() {
        return view('ValidarDoacao');
    }

    public function estoqueMedicamento() {
        return view('EstoqueMedicamento');
    }
}