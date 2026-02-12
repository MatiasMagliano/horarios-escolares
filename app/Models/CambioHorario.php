<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioHorario extends Model
{
    protected $table = 'cambios_horario';

    const ESTADOS = [
        'borrador',
        'autorizado',
        'firmado',
        'activo',
        'finalizado'
    ];


    protected $fillable = [
        'tipo',
        'estado',
        'motivo',
        'fecha_desde',
        'fecha_hasta',
        'autorizado_por',
        'autorizado_en',
        'firmado_por',
        'firmado_en',
        'activado_por',
        'activado_en',
        'finalizado_por',
        'finalizado_en',
        'pedido_por',
        'pedido_en',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'autorizado_en' => 'date',
        'firmado_en' => 'date',
        'activado_en' => 'date',
        'finalizado_en' => 'date',
    ];


    public function detalles()
    {
        return $this->hasMany(CambioHorarioDetalle::class);
    }

    public function documento()
    {
        return $this->hasOne(DocumentoCambio::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'pedido_por');
    }

    public function puedeAutorizar(): bool
    {
        return $this->estado === 'borrador';
    }

    public function estaActivoEnFecha($fecha): bool
    {
        return $this->estado === 'activo'
            && $fecha >= $this->fecha_desde
            && ($this->fecha_hasta === null || $fecha <= $this->fecha_hasta);
    }

    // método temporal que releva al momento de aprobar un cambio (estado activo)
    public function haySuperposicion(): bool
    {
        return CambioHorarioDetalle::where('horario_base_id', $this->horario_base_id)
            ->whereHas('cambio', function ($q) {
                $q->whereIn('estado', ['autorizado', 'firmado', 'activo'])
                    ->where(function ($query) {
                        $query->whereBetween('fecha_desde', [$this->fecha_desde, $this->fecha_hasta])
                            ->orWhereBetween('fecha_hasta', [$this->fecha_desde, $this->fecha_hasta])
                            ->orWhere(function ($q2) {
                                $q2->where('fecha_desde', '<=', $this->fecha_desde)
                                    ->where('fecha_hasta', '>=', $this->fecha_hasta);
                            });
                    });
            })
            ->exists();
    }

    // MÁQUINA DE ESTADOS: AUTORIZAR, FIRMAR, ACTIVAR, FINALIZAR
    public function autorizar(User $user)
    {
        if ($this->estado !== 'borrador') {
            throw new \Exception('Solo puede autorizarse un borrador.');
        }

        if (!$user->hasRole('aprobador')) {
            throw new \Exception('No tiene permisos para autorizar.');
        }

        $this->update([
            'estado' => 'autorizado',
            'autorizado_por' => $user->id,
            'autorizado_en' => now(),
        ]);
    }
    public function firmar(User $user)
    {
        if ($this->estado !== 'autorizado') {
            throw new \Exception('Debe estar autorizado.');
        }

        if (!$user->hasRole('secretario')) {
            throw new \Exception('No tiene permisos para firmar.');
        }

        $this->update([
            'estado' => 'firmado',
            'firmado_por' => $user->id,
            'firmado_en' => now(),
        ]);
    }
    public function activar(User $user)
    {
        if ($this->estado !== 'firmado') {
            throw new \Exception('Debe estar firmado.');
        }

        if (!$this->puedeActivarse()) {
            throw new \Exception('Existe superposición con otro cambio.');
        }

        $this->update([
            'estado' => 'activo',
            'activado_por' => $user->id,
            'activado_en' => now(),
        ]);
    }
    public function finalizar(User $user)
    {
        if ($this->estado !== 'activo') {
            throw new \Exception('Solo puede finalizarse un cambio activo.');
        }

        $this->update([
            'estado' => 'finalizado',
            'finalizado_en' => now(),
        ]);
    }

    public function puedeActivarse(): bool
    {
        foreach ($this->detalles as $detalle) {

            $conflicto = CambioHorarioDetalle::where('horario_base_id', $detalle->horario_base_id)
                ->whereHas('cambio', function ($q) {
                    $q->where('estado', 'activo')
                        ->where('id', '!=', $this->id)
                        ->where(function ($query) {
                            $query->where(function ($q2) {
                                $q2->whereNull('fecha_hasta')
                                    ->orWhere('fecha_hasta', '>=', $this->fecha_desde);
                            })->where('fecha_desde', '<=', $this->fecha_hasta ?? $this->fecha_desde);
                        });
                })
                ->exists();

            if ($conflicto) {
                return false;
            }
        }

        return true;
    }

    // método en progreso que pone envigencia un cambio de horario
    public function horarioEfectivo($fecha)
    {
        $detalle = $this->cambioActivoEnFecha($fecha);

        if (! $detalle) {
            return $this;
        }

        return (object) [
            'docente_id' => $detalle->n_doc_id ?? $this->docente_id,
            'bloque_id' => $detalle->n_blq_id ?? $this->bloque_id,
            'curso_id' => $detalle->n_curso_id ?? $this->curso_id,
            'dia' => $detalle->dia_nuevo ?? $this->dia,
            'es_cambio' => true,
            'cambio_id' => $detalle->cambio->id,
        ];
    }
}
