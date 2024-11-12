<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\GrupoEdad;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = [
            [
                'titulo' => 'Introducción a la Programación',
                'descripcion' => 'Curso básico de programación para principiantes',
                'categoria' => 'Programación',
                'grupo_edad' => '14-16'
            ],
            [
                'titulo' => 'Matemáticas Divertidas',
                'descripcion' => 'Aprende matemáticas de forma divertida',
                'categoria' => 'Matemáticas',
                'grupo_edad' => '5-8'
            ],
            [
                'titulo' => 'Ciencias para Niños',
                'descripcion' => 'Descubre el mundo de la ciencia',
                'categoria' => 'Ciencias',
                'grupo_edad' => '9-13'
            ],
            [
                'titulo' => 'Inglés Básico',
                'descripcion' => 'Primeros pasos en el idioma inglés',
                'categoria' => 'Idiomas',
                'grupo_edad' => '5-8'
            ],
            [
                'titulo' => 'Arte y Creatividad',
                'descripcion' => 'Desarrolla tu creatividad artística',
                'categoria' => 'Arte',
                'grupo_edad' => '9-13'
            ],
            [
                'titulo' => 'Música para Todos',
                'descripcion' => 'Introducción al mundo musical',
                'categoria' => 'Música',
                'grupo_edad' => '5-8'
            ]
        ];

        foreach ($cursos as $curso) {
            $categoria = Categoria::where('nombre', $curso['categoria'])->first();
            $grupoEdad = GrupoEdad::where('rango', $curso['grupo_edad'])->first();

            if ($categoria && $grupoEdad) {
                Curso::create([
                    'titulo' => $curso['titulo'],
                    'descripcion' => $curso['descripcion'],
                    'categoria_id' => $categoria->id,
                    'grupo_edad_id' => $grupoEdad->id
                ]);
            }
        }
    }
}