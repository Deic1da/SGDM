<?php

use App\Http\Controllers\PaginasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FarmaceuticoController;
use App\Http\Controllers\MedicamentoDoacaoController;
use App\Http\Controllers\PerfilController;

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
    Route::post('/CadastroFarmaceutico', [FarmaceuticoController::class, 'store'])
        ->name('cadastro-farmaceutico.store');

    // Cadastro de medicamento para doacao
    Route::get('/CadastroMedicamento', [PaginasController::class, 'cadastroMedicamento'])
        ->name('cadastro-medicamento');

    // Cadastro de ponto de coleta (disponivel para usuario autenticado)
    Route::get('/CadastroPontoColeta', [PaginasController::class, 'cadastroPontoColeta'])
        ->name('cadastro-ponto-coleta');
    Route::post('/CadastroPontoColeta', [EntidadeController::class, 'store'])
        ->name('cadastro-ponto-coleta.store');
    Route::get('/PontosColeta', [EntidadeController::class, 'index'])
        ->name('pontos-coleta.index');
    Route::get('/PontosColeta/{entidade}/editar', [EntidadeController::class, 'edit'])
        ->name('pontos-coleta.edit');
    Route::put('/PontosColeta/{entidade}', [EntidadeController::class, 'update'])
        ->name('pontos-coleta.update');
    Route::patch('/PontosColeta/{entidade}/reativar', [EntidadeController::class, 'reativar'])
        ->name('pontos-coleta.reativar');
    Route::delete('/PontosColeta/{entidade}', [EntidadeController::class, 'destroy'])
        ->name('pontos-coleta.destroy');

    Route::post('/CadastroMedicamento', [MedicamentoDoacaoController::class, 'store'])
        ->name('cadastro-medicamento.store');

    Route::post('/Perfil/Foto', [PerfilController::class, 'atualizarFoto'])
        ->name('perfil-foto.update');

    // Fluxo restrito a farmaceutico ativo
    Route::middleware('farmaceutico.ativo')->group(function () {
        Route::get('/Entidades/{entidade}/ValidarDoacao', [PaginasController::class, 'validarDoacao'])
            ->name('validar-doacao');

        Route::get('/Entidades/{entidade}/Estoque', [EstoqueController::class, 'index'])
            ->name('estoque-medicamento');
    });
});
