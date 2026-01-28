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
        'es_especial',
    ];

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class, 'bloque_id');
    }
}
