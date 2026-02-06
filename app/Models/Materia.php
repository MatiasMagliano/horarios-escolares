<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = [
        'nombre',
        'curso_id',
        'horas_totales', // en módulos
    ];

    // relaciones con tabla pivot curso_materia
    public function cursoMaterias()
    {
        return $this->hasMany(CursoMateria::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class,'curso_materia')
            ->withPivot('horas_totales')
            ->withTimestamps();
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }

    /**
     * Cantidad de módulos asignados en el horario base
     */
    public function modulosAsignados(): int
    {
        return $this->horariosBase()->count();
    }

    /**
     * Indica si la materia cumple la carga horaria
     */
    public function cargaCompleta(): bool
    {
        return $this->modulosAsignados() === $this->horas_totales;
    }
}
