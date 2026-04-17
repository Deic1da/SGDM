<?php

use App\Http\Controllers\PaginasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\MedicamentoDoacaoController;

// Rotas publicas
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Autenticacao
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Area autenticada
Route::middleware('auth')->group(function () {

    // Paginas gerais
    Route::get('/PaginaInicial', [PaginasController::class, 'inicio'])
        ->name('pagina-inicial');

    Route::get('/Descarte', [PaginasController::class, 'descarte'])
        ->name('descarte');

    // Cadastro de farmaceutico
    Route::get('/CadastroFarmaceutico', [PaginasController::class, 'cadastroFarmaceutico'])
        ->name('cadastro-farmaceutico');

    // Cadastro de medicamento para doacao
    Route::get('/CadastroMedicamento', [PaginasController::class, 'cadastroMedicamento'])
        ->name('cadastro-medicamento');

    // Cadastro de ponto de coleta (disponivel para usuario autenticado)
    Route::get('/CadastroPontoColeta', [PaginasController::class, 'cadastroPontoColeta'])
        ->name('cadastro-ponto-coleta');
    Route::post('/CadastroPontoColeta', [EntidadeController::class, 'store'])
        ->name('cadastro-ponto-coleta.store');

    Route::post('/CadastroMedicamento', [MedicamentoDoacaoController::class, 'store'])
        ->name('cadastro-medicamento.store');

    // Fluxo restrito a farmaceutico ativo
    Route::middleware('farmaceutico.ativo')->group(function () {
        Route::get('/ValidarDoacao', [PaginasController::class, 'validarDoacao'])
            ->name('validar-doacao');

        Route::get('/EstoqueMedicamento', [EstoqueController::class, 'index'])
            ->name('estoque-medicamento');
    });
});
