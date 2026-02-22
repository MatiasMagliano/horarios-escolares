<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'anio',
        'division',
        'ciclo',
        'turno',
    ];

    // relación con pivot curso_materia
    public function cursoMaterias()
    {
        return $this->hasMany(CursoMateria::class);
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'curso_materia')
            ->withPivot('horas_totales')
            ->withTimestamps();
    }

    // accesor designación de turno
    public function getTurnoDesignacionAttribute(): string
    {
        return match ($this->turno) {
            'maniana' => 'Mañana',
            'tarde'   => 'Tarde',
            default   => '—',
        };
    }

    // accesor nombre completo del curso
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->anio}° {$this->division} ({$this->turno_designacion})";
    }

    // accesor designacion de ciclo
    public function getCicloAttribute(): string
    {
        return $this->anio <= 3 ? 'CB' : 'CE';
    }
}
