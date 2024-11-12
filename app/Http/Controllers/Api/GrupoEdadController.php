<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GrupoEdad;
use Illuminate\Http\Request;

class GrupoEdadController extends Controller
{
    public function index()
    {
        try {
            $gruposEdad = GrupoEdad::all();
            \Log::info('Grupos de edad cargados:', ['count' => $gruposEdad->count()]);
            return response()->json($gruposEdad);
        } catch (\Exception $e) {
            \Log::error('Error cargando grupos de edad:', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error al cargar grupos de edad',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
