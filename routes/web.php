<?php

use Illuminate\Support\Facades\Route;

Route::get('/Login', function () {
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