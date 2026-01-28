<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = [
        'nombre',
        'curso_id',
        'horas_totales', // en módulos
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }

    /**
     * Cantidad de módulos asignados en el horario base
     */
    public function modulosAsignados(): int
    {
        return $this->horariosBase()->count();
    }

    /**
     * Indica si la materia cumple la carga horaria
     */
    public function cargaCompleta(): bool
    {
        return $this->modulosAsignados() === $this->horas_totales;
    }
}
