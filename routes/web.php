<?php

use Illuminate\Support\Facades\Route;

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