<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'nombre_completo',
        'dni',
        'nacimiento',
        'email',
        'activo',
    ];

    // casteo de fechas
    protected $casts = [
        'nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // accesor para mostrar DNI con puntos cada 3 dígitos
    public function getDocumentoAttribute(): string
    {
        return preg_replace('/(?<=\d)(?=(\d{3})+(?!\d))/', '.', $this->dni);
    }

    // Accesor para calcular la edad
    public function getEdadAttribute()
    {
        return $this->nacimiento->diffForHumans();
    }

    public function cursoMaterias()
    {
        return $this->hasMany(CursoMateria::class);
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }
}
