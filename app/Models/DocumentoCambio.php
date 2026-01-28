<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoCambio extends Model
{
    protected $fillable = [
        'cambio_horario_id',
        'archivo',
        'fecha_firma',
    ];

    public function cambio()
    {
        return $this->belongsTo(CambioHorario::class);
    }
}
