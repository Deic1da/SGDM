<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('Login');
});

Route::get('/CadastroFarmaceutico', function () {
    return view('CadastroFarmaceutico');
});

Route::get('/CadastroMedicamento', function () {
    return view('CadastroMedicamento');
});

Route::get('/Descarte', function () {
    return view('Descarte');
});

Route::get('/ValidarDoacao', function () {
    return view('ValidarDoacao');
});

Route::get('/PaginaInicial', function () {
    return view('PaginaInicial');
});


    
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
