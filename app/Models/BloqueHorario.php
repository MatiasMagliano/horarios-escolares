<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueHorario extends Model
{
    protected $table = 'bloques_horarios';

    protected $fillable = [
        'nombre',
        'turno',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'tipo',
    ];

    // casteo de las variables de tiempo para que sean formateadas al momento de usarlas
    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'duracion_minutos' => 'integer',
        'orden' => 'integer',
    ];

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class, 'bloque_id');
    }
}
