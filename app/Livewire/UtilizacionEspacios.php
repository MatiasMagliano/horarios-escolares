<?php

namespace App\Livewire;

use App\Models\BloqueHorario;
use App\Models\Curso;
use App\Models\EspacioFisico;
use App\Models\HorarioBase;
use Illuminate\Support\Collection;
use Livewire\Component;

class UtilizacionEspacios extends Component
{
    public ?int $espacioSeleccionado = null;
    public array $cursoIdsVisibles = [];

    public function updatedEspacioSeleccionado($value): void
    {
        if (!$value) {
            $this->cursoIdsVisibles = [];
            return;
        }

        $this->cursoIdsVisibles = $this->cursosAfectados
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();
    }

    public function getEspaciosProperty(): Collection
    {
        return EspacioFisico::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    public function getCursosAfectadosProperty(): Collection
    {
        if (!$this->espacioSeleccionado) {
            return collect();
        }

        return Curso::query()
            ->whereHas('horariosBase', function ($query) {
                $query->vigente()
                    ->whereBetween('dia_semana', [1, 5])
                    ->whereHas('cursoMateria', function ($cursoMateriaQuery) {
                        $cursoMateriaQuery->where('espacio_fisico_id', $this->espacioSeleccionado);
                    });
            })
            ->orderBy('anio')
            ->orderBy('division')
            ->get();
    }

    public function getGrillasProperty(): Collection
    {
        $horarios = $this->getHorariosVisibles();

        if ($horarios->isEmpty()) {
            return collect();
        }

        $franjas = $horarios
            ->pluck('bloque.turno')
            ->map(fn ($turno) => $this->franjaDeTurno($turno))
            ->unique()
            ->values()
            ->all();

        $bloques = $this->getBloquesForFranjas($franjas);
        $horariosAgrupados = $horarios
            ->groupBy(fn ($horario) => $this->franjaDeTurno($horario->bloque->turno))
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
            ->mapWithKeys(function ($franja) use ($bloques, $horariosAgrupados) {
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

    public function getAdvertenciasProperty(): array
    {
        $horarios = $this->getHorariosVisibles();

        if ($horarios->isEmpty()) {
            return [];
        }

        $espacio = $this->espacios->firstWhere('id', $this->espacioSeleccionado);
        $advertencias = [];

        $conflictos = $horarios
            ->groupBy(fn ($horario) => implode('-', [
                $this->franjaDeTurno($horario->bloque->turno),
                $horario->bloque->orden,
                $horario->dia_semana,
            ]))
            ->filter(fn ($grupo) => $grupo->count() > 1);

        foreach ($conflictos as $grupo) {
            $primerHorario = $grupo->first();
            $bloque = $primerHorario->bloque;
            $franja = $this->designacionTurno($this->franjaDeTurno($bloque->turno));
            $dia = $this->designacionDia((int) $primerHorario->dia_semana);
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

    private function getBloquesForFranjas(array $franjas): Collection
    {
        return BloqueHorario::query()
            ->whereIn('turno', $franjas)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');
    }

    private function franjaDeTurno(string $turno): string
    {
        return match ($turno) {
            'maniana', 'contraturno_tarde' => 'maniana',
            'tarde', 'contraturno_maniana' => 'tarde',
            default => $turno,
        };
    }

    public function designacionTurno(string $franja): string
    {
        return match ($franja) {
            'maniana' => 'Mañana',
            'tarde' => 'Tarde',
            default => 'Turno',
        };
    }

    private function getHorariosVisibles(): Collection
    {
        if (!$this->espacioSeleccionado) {
            return collect();
        }

        $cursoIds = collect($this->cursoIdsVisibles)
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
            ->whereHas('cursoMateria', function ($query) {
                $query->where('espacio_fisico_id', $this->espacioSeleccionado);
            })
            ->get();
    }

    private function designacionDia(int $dia): string
    {
        return match ($dia) {
            1 => 'lunes',
            2 => 'martes',
            3 => 'miércoles',
            4 => 'jueves',
            5 => 'viernes',
            default => 'día no definido',
        };
    }

    public function render()
    {
        return view('livewire.utilizacion-espacios');
    }
}
