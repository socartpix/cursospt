<?php

namespace App\Http\Controllers\AdminCursoController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso; 
class AdminCursoController extends Controller
{

public function index()
    {
        $cursos = Curso::with(['categoria', 'grupoEdad'])->paginate(10);
        return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $gruposEdad = GrupoEdad::all();
        return view('admin.cursos.create', compact('categorias', 'gruposEdad'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'categoria_id' => 'required|exists:categorias,id',
            'grupo_edad_id' => 'required|exists:grupo_edads,id',
        ]);

        Curso::create($validatedData);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso creado exitosamente');
    }

    public function show(Curso $curso)
    {
        $curso->load(['videos', 'categoria', 'grupoEdad']);
        return view('admin.cursos.show', compact('curso'));
    }

    public function edit(Curso $curso)
    {
        $categorias = Categoria::all();
        $gruposEdad = GrupoEdad::all();
        return view('admin.cursos.edit', compact('curso', 'categorias', 'gruposEdad'));
    }

    public function update(Request $request, Curso $curso)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'categoria_id' => 'required|exists:categorias,id',
            'grupo_edad_id' => 'required|exists:grupo_edads,id',
        ]);

        $curso->update($validatedData);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso eliminado exitosamente');
    }

    public function estudiantes(Curso $curso)
    {
        $estudiantes = $curso->usuarios()->paginate(10);
        return view('admin.cursos.estudiantes', compact('curso', 'estudiantes'));
    }
}
