<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioHorario extends Model
{
    protected $table = 'cambios_horario';

    protected $fillable = [
        'tipo',
        'estado',
        'motivo',
        'fecha_desde',
        'fecha_hasta',
        'autorizado_por',
    ];

    public function detalles()
    {
        return $this->hasMany(CambioHorarioDetalle::class);
    }

    public function documento()
    {
        return $this->hasOne(DocumentoCambio::class);
    }

    public function estaActivoEnFecha($fecha): bool
    {
        return $this->estado === 'activo'
            && $fecha >= $this->fecha_desde
            && ($this->fecha_hasta === null || $fecha <= $this->fecha_hasta);
    }
}
