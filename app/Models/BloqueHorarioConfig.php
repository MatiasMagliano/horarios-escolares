<?php

namespace App\Models;

use App\Models\Concerns\BelongsToInstitucion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloqueHorarioConfig extends Model
{
    use BelongsToInstitucion;

    protected $table = 'bloque_horario_configs';

    protected $fillable = [
        'institucion_id',
        'nombre',
        'turno',
        'orden',
        'hora_inicio',
        'hora_fin',
        'tipo',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /**
     * Relación inversa a Institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /**
     * Verifica si este bloque es un recreo
     */
    public function esRecreo(): bool
    {
        return $this->tipo === 'recreo';
    }

    /**
     * Calcula la duración en minutos
     */
    public function calcularDuracion(): int
    {
        $inicio = is_string($this->hora_inicio) 
            ? \Carbon\Carbon::createFromTimeString($this->hora_inicio)
            : $this->hora_inicio;
        
        $fin = is_string($this->hora_fin)
            ? \Carbon\Carbon::createFromTimeString($this->hora_fin)
            : $this->hora_fin;
        
        if (!$inicio || !$fin) {
            return 0;
        }

        return $inicio->diffInMinutes($fin);
    }

    /**
     * Scope: obtener bloques de un turno específico ordenados
     */
    public function scopePorTurno($query, string $turno)
    {
        return $query->where('turno', $turno)->orderBy('orden');
    }

    /**
     * Scope: obtener bloques de una institución específica
     */
    public function scopeDelInstitucion($query, int $institucionId)
    {
        return $query->where('institucion_id', $institucionId);
    }
}
