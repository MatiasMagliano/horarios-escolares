<?php

namespace App\Models;

use App\Models\Concerns\BelongsToInstitucion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HorarioBase extends Model
{
    use BelongsToInstitucion;

    protected $table = 'horarios_base';

    protected $fillable = [
        'institucion_id',
        'curso_id',
        'curso_materia_id',
        'bloque_id',
        'dia_semana',
        'vigente_desde',
        'vigente_hasta',
        'es_vigente',
        'cambio_horario_id',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    public function cursoMateria()
    {
        return $this->belongsTo(CursoMateria::class, 'curso_materia_id');
    }
    public function bloque()
    {
        return $this->belongsTo(BloqueHorario::class, 'bloque_id');
    }

    public function scopeVigente(Builder $query): Builder
    {
        return $query->where('es_vigente', true)->whereNull('vigente_hasta');
    }

    public function cmDocenteVigente()
    {
        return $this->hasOne(CmDocente::class, 'curso_materia_id', 'curso_materia_id')
            ->vigente();
    }

    public function docenteVigente()
    {
        return $this->hasOneThrough(
            Docente::class,
            CmDocente::class,
            'curso_materia_id',
            'id',
            'curso_materia_id',
            'docente_id'
        )->where('cm_docente.es_vigente', true)->whereNull('cm_docente.vigente_hasta');
    }

    public function scopeConDocenteVigente(Builder $query): Builder
    {
        return $query->with(['cursoMateria.materia', 'docenteVigente', 'bloque']);
    }
}
