<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    public const ESPACIO_AULA = 'aula';
    public const ESPACIO_LAB_INFORMATICA = 'lab-informatica';
    public const ESPACIO_LAB_ELECTRONICA = 'lab-electronica';
    public const ESPACIO_LAB_TALLER = 'lab-taller';
    public const ESPACIO_PATIO = 'patio';

    protected $fillable = [
        'nombre',
        'espacio_requerido',
    ];

    public static function espaciosDisponibles(): array
    {
        return [
            self::ESPACIO_AULA,
            self::ESPACIO_LAB_INFORMATICA,
            self::ESPACIO_LAB_ELECTRONICA,
            self::ESPACIO_LAB_TALLER,
            self::ESPACIO_PATIO,
        ];
    }

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
}
