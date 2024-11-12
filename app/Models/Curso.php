<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Categoria; 
use App\Models\GrupoEdad; 

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'grupo_edad_id'
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function grupoEdad(): BelongsTo
    {
        return $this->belongsTo(GrupoEdad::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'curso_usuario')
                    ->withPivot('progreso')
                    ->withTimestamps();
    }
}