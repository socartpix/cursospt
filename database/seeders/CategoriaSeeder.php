<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;  

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Programación',
                'descripcion' => 'Cursos de programación y desarrollo de software'
            ],
            [
                'nombre' => 'Matemáticas',
                'descripcion' => 'Cursos de matemáticas básicas y avanzadas'
            ],
            [
                'nombre' => 'Ciencias',
                'descripcion' => 'Cursos de ciencias naturales y experimentales'
            ],
            [
                'nombre' => 'Idiomas',
                'descripcion' => 'Cursos de diferentes idiomas'
            ],
            [
                'nombre' => 'Arte',
                'descripcion' => 'Cursos de arte y creatividad'
            ],
            [
                'nombre' => 'Música',
                'descripcion' => 'Cursos de música y educación musical'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}

