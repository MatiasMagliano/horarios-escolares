<?php

namespace App\Support\Horarios;

use App\Models\BloqueHorario;
use App\Models\EspacioFisico;
use App\Models\HorarioBase;
use Illuminate\Support\Collection;

class UtilizacionEspaciosGridBuilder
{
    public function build(?int $espacioId, array $cursoIdsVisibles): Collection
    {
        $horarios = $this->getHorariosVisibles($espacioId, $cursoIdsVisibles);

        if ($horarios->isEmpty()) {
            return collect();
        }

        $franjas = $horarios
            ->pluck('bloque.turno')
            ->map(fn ($turno) => TurnoHelper::franjaDeTurno($turno))
            ->unique()
            ->values()
            ->all();

        $bloques = $this->getBloquesForFranjas($franjas);
        $horariosAgrupados = $horarios
            ->groupBy(fn ($horario) => TurnoHelper::franjaDeTurno($horario->bloque->turno))
            ->map(function ($items) {
                return $items->groupBy(fn ($horario) => $horario->bloque->orden)
                    ->map(function ($porBloque) {
                        return $porBloque->groupBy('dia_semana')
                            ->map(fn ($porDia) => $porDia->sortBy(
                                fn ($horario) => sprintf(
                                    '%02d-%s-%s',
                                    $horario->curso->anio,
                                    $horario->curso->division,
                                    $horario->cursoMateria?->materia?->nombre ?? ''
                                )
                            )->values());
                    });
            });

        return collect($franjas)
            ->mapWithKeys(function (string $franja) use ($bloques, $horariosAgrupados) {
                $grilla = $bloques->get($franja, collect())
                    ->mapWithKeys(function ($bloque) use ($horariosAgrupados, $franja) {
                        return [
                            $bloque->orden => collect([
                                'bloque' => $bloque,
                                'dias' => $horariosAgrupados->get($franja)?->get($bloque->orden) ?? collect(),
                            ]),
                        ];
                    });

                return [$franja => $grilla];
            });
    }

    public function warnings(?int $espacioId, array $cursoIdsVisibles): array
    {
        $horarios = $this->getHorariosVisibles($espacioId, $cursoIdsVisibles);

        if ($horarios->isEmpty()) {
            return [];
        }

        $espacio = $espacioId ? EspacioFisico::query()->find($espacioId) : null;
        $advertencias = [];

        $conflictos = $horarios
            ->groupBy(fn ($horario) => implode('-', [
                TurnoHelper::franjaDeTurno($horario->bloque->turno),
                $horario->bloque->orden,
                $horario->dia_semana,
            ]))
            ->filter(fn ($grupo) => $grupo->count() > 1);

        foreach ($conflictos as $grupo) {
            $primerHorario = $grupo->first();
            $bloque = $primerHorario->bloque;
            $franja = TurnoHelper::designacionTurno(TurnoHelper::franjaDeTurno($bloque->turno));
            $dia = TurnoHelper::designacionDia((int) $primerHorario->dia_semana);
            $cursos = $grupo
                ->map(fn ($horario) => $horario->curso->anio . 'º ' . $horario->curso->division)
                ->unique()
                ->values()
                ->implode(', ');

            $advertencias[] = sprintf(
                '%s tiene %d cursos superpuestos el %s de %s (%s - %s): %s.',
                $espacio?->nombre ?? 'El espacio seleccionado',
                $grupo->count(),
                $dia,
                $franja,
                $bloque->hora_inicio->format('H:i'),
                $bloque->hora_fin->format('H:i'),
                $cursos
            );
        }

        return $advertencias;
    }

    public function getHorariosVisibles(?int $espacioId, array $cursoIdsVisibles): Collection
    {
        if (!$espacioId) {
            return collect();
        }

        $cursoIds = collect($cursoIdsVisibles)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        if ($cursoIds === []) {
            return collect();
        }

        return HorarioBase::query()
            ->conDocenteVigente()
            ->with(['curso', 'bloque', 'cursoMateria.materia'])
            ->vigente()
            ->whereIn('curso_id', $cursoIds)
            ->whereBetween('dia_semana', [1, 5])
            ->whereHas('cursoMateria', function ($query) use ($espacioId) {
                $query->where('espacio_fisico_id', $espacioId);
            })
            ->get();
    }

    private function getBloquesForFranjas(array $franjas): Collection
    {
        return BloqueHorario::query()
            ->whereIn('turno', $franjas)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');
    }
}
