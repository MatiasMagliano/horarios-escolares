<?php

namespace App\Support\Dashboard;

use App\Models\HorarioBase;
use Illuminate\Support\Collection;

class DocenteSuperposicionDetector
{
    public function detect(): Collection
    {
        return HorarioBase::query()
            ->vigente()
            ->whereHas('cmDocenteVigente', function ($q) {
                $q->where('es_vigente', true);
            })
            ->with([
                'curso',
                'cursoMateria.materia',
                'cmDocenteVigente.docente',
                'bloque',
            ])
            ->get()
            ->groupBy(function (HorarioBase $horario) {
                return implode(':', [
                    $horario->cmDocenteVigente->docente_id,
                    $horario->dia_semana,
                    $horario->bloque_id,
                ]);
            })
            ->filter(fn (Collection $items) => $items->count() > 1)
            ->map(function (Collection $items) {
                $primero = $items->first();
                $bloque = $primero->bloque;
                $docente = $primero->cmDocenteVigente->docente;

                return [
                    'docente_id' => $docente->id,
                    'docente_nombre' => $docente->nombre_completo,
                    'dia_semana' => $primero->dia_semana,
                    'dia_nombre' => $this->diaNombre($primero->dia_semana),
                    'bloque_id' => $primero->bloque_id,
                    'bloque_nombre' => $bloque->nombre,
                    'hora_inicio' => $bloque->hora_inicio?->format('H:i'),
                    'hora_fin' => $bloque->hora_fin?->format('H:i'),
                    'total_asignaciones' => $items->count(),
                    'asignaciones' => $items
                        ->map(function (HorarioBase $horario) {
                            return [
                                'curso' => $horario->curso->nombre_completo,
                                'materia' => $horario->cursoMateria->materia->nombre,
                            ];
                        })
                        ->values(),
                ];
            })
            ->sortBy(fn (array $conflicto) => sprintf(
                '%s-%02d-%s',
                mb_strtolower($conflicto['docente_nombre']),
                $conflicto['dia_semana'],
                $conflicto['hora_inicio'] ?? '00:00'
            ))
            ->values();
    }

    private function diaNombre(?int $dia): string
    {
        return match ($dia) {
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
            default => '—',
        };
    }
}
