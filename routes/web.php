<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicamentoDoacaoController;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect('/PaginaInicial');
    }

    return view('Login');
})->name('login');

Route::middleware('auth')->group(function () {
    
    Route::get('/CadastroFarmaceutico', function () {
        return view('CadastroFarmaceutico');
    })->name('cadastro-farmaceutico');
    
    Route::get('/CadastroMedicamento', function () {
        return view('CadastroMedicamento');
    })->name('cadastro-medicamento');

    Route::post('/CadastroMedicamento', [MedicamentoDoacaoController::class, 'store'])
        ->name('cadastro-medicamento.store');
    
    Route::get('/Descarte', function () {
        return view('Descarte');
    })->name('descarte');

    Route::middleware('farmaceutico.ativo')->group(function () {
        Route::get('/ValidarDoacao', function () {
            return view('ValidarDoacao');
        })->name('validar-doacao');

        Route::get('/EstoqueMedicamento', function () {
            return view('EstoqueMedicamento');
        })->name('estoque-medicamento');
    });
    
    Route::get('/PaginaInicial', function () {
        return view('PaginaInicial');
    })->name('pagina-inicial');

});





Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
