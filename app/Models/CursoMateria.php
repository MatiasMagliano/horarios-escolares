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

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function horarios()
    {
        return $this->hasMany(HorarioBase::class);
    }
}
