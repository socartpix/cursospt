<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\CategoriaController; 
use App\Http\Controllers\Api\GrupoEdadController;

use App\Http\Controllers\Api\UserController;
Route::prefix('v1')->group(function () {
    // Rutas públicas
    Route::get('categorias', [CategoriaController::class, 'index']); 
    Route::get('grupos-edad', [GrupoEdadController::class, 'index']); 
  
    // Autenticación
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Rutas públicas de cursos
    Route::get('cursos/buscar', [CursoController::class, 'buscar']);
    Route::get('cursos', [CursoController::class, 'index']);
    Route::get('cursos/{curso}', [CursoController::class, 'show']);

    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        // Usuario

        Route::apiResource('cursos', 'App\Http\Controllers\Api\CursoController');
        Route::get('user', [AuthController::class, 'user']);
        Route::get('users', [UserController::class, 'index']);
        //Route::get('users', [AuthController::class, 'index']);
        Route::post('logout', [AuthController::class, 'logout']);

        // Cursos y Videos
        Route::get('mis-cursos', [CursoController::class, 'misCursos']);
        Route::post('cursos/{curso}/inscribir', [CursoController::class, 'inscribir']);
        
        // Videos - Simplificado
        Route::get('cursos/{curso}/videos', [VideoController::class, 'index']);
        Route::post('cursos/{curso}/videos', [VideoController::class, 'store']);
        Route::put('videos/{video}', [VideoController::class, 'update']);
        Route::delete('videos/{video}', [VideoController::class, 'destroy']);

        //Estudiantes
        
    });
});