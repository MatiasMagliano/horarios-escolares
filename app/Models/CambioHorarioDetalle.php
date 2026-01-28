<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioHorarioDetalle extends Model
{
    protected $fillable = [
        'cambio_horario_id',
        'horario_base_id',
        'docente_nuevo_id',
        'bloque_nuevo_id',
        'dia_nuevo',
        'curso_nuevo_id',
        'observaciones',
    ];

    public function cambio()
    {
        return $this->belongsTo(CambioHorario::class);
    }

    public function horarioBase()
    {
        return $this->belongsTo(HorarioBase::class);
    }
}
