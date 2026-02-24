<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CursoMateria extends Model
{
    protected $table = 'curso_materia';

    protected $fillable = [
        'curso_id',
        'materia_id',
        'docente_id',
        'horas_totales',
        'vigente_desde',
        'vigente_hasta',
        'es_vigente',
        'cambio_horario_id',
    ];

    public function curso() { return $this->belongsTo(Curso::class); }

    public function materia() { return $this->belongsTo(Materia::class); }

    public function docente() { return $this->belongsTo(Docente::class); }

    public function horarioBase() { return $this->hasMany(HorarioBase::class); }

    public function scopeVigente(Builder $query): Builder
    {
        return $query->where('es_vigente', true)->whereNull('vigente_hasta');
    }
}
