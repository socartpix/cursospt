<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\CategoriaController; 
use App\Http\Controllers\Api\GrupoEdadController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController; 
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;

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
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);

        // Cursos
        Route::get('mis-cursos', [CursoController::class, 'misCursos']);
        Route::post('cursos/{curso}/inscribir', [CursoController::class, 'inscribir']);
        
        // Videos - Rutas principales
        Route::get('cursos/{curso}/videos', [VideoController::class, 'index']);
        Route::post('cursos/{curso}/videos', [VideoController::class, 'store']);
        Route::put('videos/{video}', [VideoController::class, 'update']);
        Route::delete('videos/{video}', [VideoController::class, 'destroy']);
        Route::post('videos/{video}/comentar', [VideoController::class, 'comentar']);
        Route::post('videos/{video}/like', [VideoController::class, 'like']);
        Route::post('videos/{video}/progreso', [VideoController::class, 'actualizarProgreso']);

        // Rutas de administrador
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::resource('cursos', CursoController::class);
            Route::get('cursos/{curso}/estudiantes', [AdminCursoController::class, 'estudiantes']);
            Route::get('videos/{video}/comentarios', [AdminVideoController::class, 'comentarios']);
            Route::put('comentarios/{comentario}/aprobar', [AdminVideoController::class, 'aprobarComentario']);
        });
    });
});