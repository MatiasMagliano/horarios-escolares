<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioBase extends Model
{
    protected $table = 'horarios_base';

    protected $fillable = [
        'curso_id',
        'materia_id',
        'docente_id',
        'bloque_id',
        'dia_semana',
    ];

    public function curso()   { return $this->belongsTo(Curso::class); }
    public function materia() { return $this->belongsTo(Materia::class); }
    public function docente() { return $this->belongsTo(Docente::class); }
    public function bloque()  { return $this->belongsTo(BloqueHorario::class, 'bloque_id'); }
}
