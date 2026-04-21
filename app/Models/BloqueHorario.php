<?php

namespace App\Models;

use App\Models\Concerns\BelongsToInstitucion;
use Illuminate\Database\Eloquent\Model;

class BloqueHorario extends Model
{
    use BelongsToInstitucion;

    protected $table = 'bloques_horarios';

    protected $fillable = [
        'institucion_id',
        'nombre',
        'turno',
        'orden',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'tipo',
        'es_editable',
    ];

    // casteo de las variables de tiempo para que sean formateadas al momento de usarlas
    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'duracion_minutos' => 'integer',
        'orden' => 'integer',
        'es_editable' => 'boolean',
    ];

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class, 'bloque_id');
    }
}
