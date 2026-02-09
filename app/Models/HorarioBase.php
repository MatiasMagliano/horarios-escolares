<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioBase extends Model
{
    protected $table = 'horarios_base';

    protected $fillable = [
        'curso_id',
        'curso_materia_id',
        'bloque_id',
        'dia_semana',
    ];

    public function curso()   { return $this->belongsTo(Curso::class); }
    public function cursoMateria(){ return $this->belongsTo(CursoMateria::class, 'curso_materia_id'); }
    public function bloque()  { return $this->belongsTo(BloqueHorario::class, 'bloque_id'); }

}
