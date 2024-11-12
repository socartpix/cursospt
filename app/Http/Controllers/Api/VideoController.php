<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Curso;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Curso $curso)
    {
        try {
            $videos = $curso->videos()
                ->with(['categoria'])
                ->get();

            return response()->json($videos);
        } catch (\Exception $e) {
            \Log::error('Error al cargar videos:', [
                'error' => $e->getMessage(),
                'curso_id' => $curso->id
            ]);
            return response()->json([
                'error' => 'Error al cargar videos',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request, Curso $curso)
    {
        \Log::info('Intentando crear video', [
            'curso_id' => $curso->id,
            'request' => $request->all()
        ]);

        try {
            $video = $curso->videos()->create([
                'titulo' => $request->titulo,
                'url_youtube' => $request->url_youtube,
                'descripcion' => $request->descripcion,
                'categoria_id' => $request->categoria_id
            ]);

            return response()->json($video, 201);
        } catch (\Exception $e) {
            \Log::error('Error creando video', [
                'error' => $e->getMessage(),
                'curso_id' => $curso->id
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }










    public function update(Request $request, Video $video)
    {
        try {
            $validatedData = $request->validate([
                'titulo' => 'required|string|max:255',
                'url_youtube' => 'required|string',
                'descripcion' => 'required|string',
                'categoria_id' => 'required|exists:categorias,id'
            ]);

            $video->update($validatedData);

            return response()->json([
                'message' => 'Video actualizado exitosamente',
                'video' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar video',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Video $video)
    {
        try {
            $video->delete();
            return response()->json([
                'message' => 'Video eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar video',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}