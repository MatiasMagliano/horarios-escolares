<?php

namespace App\Livewire;

use App\Models\BloqueHorario;
use App\Models\Curso;
use App\Models\CursoMateria;
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
        if (!$this->espacioSeleccionado || empty($this->cursoIdsVisibles)) {
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

        $horarios = HorarioBase::query()
            ->conDocenteVigente()
            ->with('curso')
            ->vigente()
            ->whereIn('curso_id', $cursoIds)
            ->whereBetween('dia_semana', [1, 5])
            ->whereHas('cursoMateria', function ($query) {
                $query->where('espacio_fisico_id', $this->espacioSeleccionado);
            })
            ->get();

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
    public function render()
    {
        return view('livewire.utilizacion-espacios');
    }
}
