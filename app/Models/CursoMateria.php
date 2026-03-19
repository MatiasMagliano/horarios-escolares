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
        'espacio_fisico_id',
    ];

    public function curso() { return $this->belongsTo(Curso::class); }

    public function materia() { return $this->belongsTo(Materia::class); }

    public function espacioFisico() { return $this->belongsTo(EspacioFisico::class); }

    public function cmDocentes() { return $this->hasMany(CmDocente::class); }

    public function cmDocenteVigente()
    {
        return $this->hasOne(CmDocente::class)->vigente();
    }

    public function horarioBase() { return $this->hasMany(HorarioBase::class); }
}
