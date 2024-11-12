<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoEdad extends Model
{
    protected $table = 'grupo_edads';
    
    protected $fillable = [
        'rango',
        'descripcion'
    ];

    // Relación con cursos
    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}