<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GrupoEdad;

class GrupoEdadSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'rango' => '5-8',
                'descripcion' => 'Niños de 5 a 8 años'
            ],
            [
                'rango' => '9-13',
                'descripcion' => 'Niños de 9 a 13 años'
            ],
            [
                'rango' => '14-16',
                'descripcion' => 'Adolescentes de 14 a 16 años'
            ],
            [
                'rango' => '16+',
                'descripcion' => 'Mayores de 16 años'
            ]
        ];

        foreach ($grupos as $grupo) {
            GrupoEdad::create($grupo);
        }
    }
}