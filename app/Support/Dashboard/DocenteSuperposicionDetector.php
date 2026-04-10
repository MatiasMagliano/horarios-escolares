<?php

namespace App\Support\Dashboard;

use App\Models\HorarioBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DocenteSuperposicionDetector
{
    public function detect(): Collection
    {
        // 1. Encontrar agrupamientos que colisionan usando SQL puro
        $superposicionesBaseQ = DB::table('horarios_base as hb')
            ->join('cm_docente as d', 'd.curso_materia_id', '=', 'hb.curso_materia_id')
            ->where('hb.es_vigente', true)
            ->whereNull('hb.vigente_hasta')
            ->where('d.es_vigente', true)
            ->whereNull('d.vigente_hasta')
            ->select('d.docente_id', 'hb.dia_semana', 'hb.bloque_id')
            ->groupBy('d.docente_id', 'hb.dia_semana', 'hb.bloque_id')
            ->havingRaw('COUNT(*) > 1');

        $superposicionesDb = $superposicionesBaseQ->get();

        if ($superposicionesDb->isEmpty()) {
            return collect();
        }

        // 2. Extraer los IDs de horarios_base exactos que forman coincidencia
        $horariosConflictivosIds = DB::table('horarios_base as hb')
            ->join('cm_docente as d', 'd.curso_materia_id', '=', 'hb.curso_materia_id')
            ->where('hb.es_vigente', true)
            ->whereNull('hb.vigente_hasta')
            ->where('d.es_vigente', true)
            ->whereNull('d.vigente_hasta')
            ->joinSub($superposicionesBaseQ, 'conflictos', function ($join) {
                $join->on('d.docente_id', '=', 'conflictos.docente_id')
                     ->on('hb.dia_semana', '=', 'conflictos.dia_semana')
                     ->on('hb.bloque_id', '=', 'conflictos.bloque_id');
            })
            ->pluck('hb.id');

        // 3. Hidratar modelos SOLAMENTE para las colisiones encontradas
        return HorarioBase::query()
            ->whereIn('id', $horariosConflictivosIds)
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
