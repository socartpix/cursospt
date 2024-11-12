<?php

use Illuminate\Support\Facades\Route;

// Esta ruta debe ir al final para manejar todas las rutas de Vue
Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');