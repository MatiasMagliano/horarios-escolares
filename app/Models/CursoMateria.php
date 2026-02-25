<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoMateria extends Model
{
    protected $table = 'curso_materia';

    protected $fillable = [
        'curso_id',
        'materia_id',
        'horas_totales',
    ];

    public function curso() { return $this->belongsTo(Curso::class); }

    public function materia() { return $this->belongsTo(Materia::class); }

    public function cmDocentes() { return $this->hasMany(CmDocente::class); }

    public function cmDocenteVigente()
    {
        return $this->hasOne(CmDocente::class)->vigente();
    }

    // Compatibilidad temporal para vistas/componentes que aún usan $cursoMateria->docente
    public function docente()
    {
        return $this->hasOneThrough(
            Docente::class,
            CmDocente::class,
            'curso_materia_id',
            'id',
            'id',
            'docente_id'
        )->where('cm_docente.es_vigente', true)->whereNull('cm_docente.vigente_hasta');
    }

    public function horarioBase() { return $this->hasMany(HorarioBase::class); }
}
