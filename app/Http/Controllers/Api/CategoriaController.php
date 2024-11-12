<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CategoriaController extends Controller
{
    public function index()
    {
        Log::info('=== Inicio de obtención de categorías ===');

        try {
            // Log antes de verificar la tabla
            Log::info('Verificando si existe la tabla categorias');
            
            if (!Schema::hasTable('categorias')) {
                Log::error('La tabla categorias NO existe en la base de datos');
                return response()->json(['error' => 'Tabla no encontrada'], 500);
            }

            Log::info('La tabla categorias existe, intentando obtener registros');
            
            // Intentar obtener las categorías
            $categorias = Categoria::all();
            
            Log::info('Categorías obtenidas exitosamente', [
                'cantidad' => $categorias->count(),
                'primera_categoria' => $categorias->first()
            ]);
            
            return response()->json($categorias);
            
        } catch (\Exception $e) {
            Log::error('Error en CategoriaController::index', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'traza' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al cargar categorías',
                'debug_message' => $e->getMessage()
            ], 500);
        }
    }
}