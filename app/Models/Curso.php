<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'anio',
        'division',
        'ciclo',
        'turno',
    ];

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }

    /**
     * Devuelve el turno contraturno lógico del curso
     */
    public function turnoContrario(): string
    {
        return match ($this->turno) {
            'maniana' => 'tarde',
            'tarde'   => 'maniana',
            default   => 'contraturno',
        };
    }
}
