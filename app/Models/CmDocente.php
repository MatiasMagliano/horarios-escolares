<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmDocente extends Model
{
    protected $table = 'cm_docente';

    protected $fillable = [
        'curso_materia_id',
        'docente_id',
        'vigente_desde',
        'vigente_hasta',
        'es_vigente',
    ];

    protected $casts = [
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
        'es_vigente' => 'boolean',
    ];

    public function cursoMateria()
    {
        return $this->belongsTo(CursoMateria::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function scopeVigente(Builder $query): Builder
    {
        return $query->where('es_vigente', true)->whereNull('vigente_hasta');
    }
}
