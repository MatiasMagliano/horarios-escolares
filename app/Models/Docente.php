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

    // RELACIONES
    public function cursoMaterias()
    {
        return $this->belongsToMany(CursoMateria::class, 'cm_docente', 'docente_id', 'curso_materia_id')
            ->withPivot(['vigente_desde', 'vigente_hasta', 'es_vigente'])
            ->withTimestamps();
    }

    public function cmDocentes()
    {
        return $this->hasMany(CmDocente::class);
    }

    public function cmDocentesVigentes()
    {
        return $this->cmDocentes()->vigente();
    }

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }

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

    public function tieneAsignacionesVigentes(): bool
    {
        return $this->cmDocentesVigentes()->exists();
    }

    public function tieneHistorialAsignaciones(): bool
    {
        return $this->cmDocentes()->exists();
    }
}
