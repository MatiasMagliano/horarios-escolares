<?php

namespace App\Models;

use App\Models\Concerns\BelongsToInstitucion;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use BelongsToInstitucion;

    protected $fillable = [
        'institucion_id',
        'nombre',
    ];

    // relaciones con tabla pivot curso_materia
    public function cursoMaterias()
    {
        return $this->hasMany(CursoMateria::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class,'curso_materia')
            ->withPivot('horas_totales', 'espacio_fisico_id')
            ->withTimestamps();
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }
}
