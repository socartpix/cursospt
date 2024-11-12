<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;  // Faltaba importar el modelo
use Illuminate\Http\Request;
    
class CursoController extends Controller
{
    public function index()
    {
        try {
            $cursos = Curso::with(['categoria', 'grupoEdad'])
                ->paginate(10);
                
            return response()->json($cursos);
        } catch (\Exception $e) {
            \Log::error('Error en CursoController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar los cursos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'categoria_id' => 'required|exists:categorias,id',
                'grupo_edad_id' => 'required|exists:grupo_edads,id',
            ]);

            $curso = Curso::create($validatedData);

            return response()->json([
                'message' => 'Curso creado exitosamente',
                'curso' => $curso
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al crear curso:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error al crear el curso',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Faltaba el método buscar que está en las rutas
    public function buscar(Request $request)
    {
        try {
            \Log::info('Iniciando búsqueda con parámetros:', $request->all());
            \Log::info('URL de la petición: ' . $request->fullUrl());
            
            $query = Curso::query();
            
            // Cargar relaciones
            $query->with(['categoria', 'grupoEdad']);
    
            // Aplicar filtros
            if ($request->filled('nombre')) {
                \Log::info('Aplicando filtro por nombre:', ['nombre' => $request->nombre]);
                $query->where('titulo', 'LIKE', "%{$request->nombre}%");
            }
    
            // Si no hay filtros, traer todos
            $cursos = $query->get();
            
            \Log::info('Cursos encontrados:', [
                'cantidad' => $cursos->count(),
                'cursos' => $cursos->toArray()
            ]);
    
            return response()->json($cursos);
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda:', [
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error al buscar cursos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Curso $curso)
    {
        try {
            $curso->load(['categoria', 'grupoEdad']);
            return response()->json($curso);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar el curso',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function misCursos(Request $request)
    {
        try {
            \Log::info('Obteniendo cursos del usuario:', [
                'user_id' => $request->user()->id
            ]);
    
            $cursos = $request->user()
                ->cursos()
                ->with(['categoria', 'grupoEdad'])
                ->get();
            
            \Log::info('Cursos encontrados:', [
                'count' => $cursos->count(),
                'cursos' => $cursos->toArray()
            ]);
                
            return response()->json($cursos);
        } catch (\Exception $e) {
            \Log::error('Error al cargar cursos del usuario:', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'error' => 'Error al cargar tus cursos',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function inscribir(Request $request, Curso $curso)
    {
        try {
            $user = $request->user();
            
            if (!$curso->usuarios()->where('user_id', $user->id)->exists()) {
                $curso->usuarios()->attach($user->id, ['progreso' => 0]);
                return response()->json(['message' => 'Inscripción exitosa']);
            }

            return response()->json(['message' => 'Ya estás inscrito en este curso'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al inscribirse al curso',
                'details' => $e->getMessage()
            ], 500);
        }
    }




    public function update(Request $request, Curso $curso)
    {
        try {
            $validatedData = $request->validate([
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'categoria_id' => 'required|exists:categorias,id',
                'grupo_edad_id' => 'required|exists:grupo_edads,id',
            ]);

            $curso->update($validatedData);

            return response()->json([
                'message' => 'Curso actualizado exitosamente',
                'curso' => $curso->refresh()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar curso:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error al actualizar el curso',
                'details' => $e->getMessage()
            ], 500);
        }
    }



}