<?php

namespace App\Livewire;

use App\Models\Curso;
use App\Models\EspacioFisico;
use App\Support\Horarios\TurnoHelper;
use App\Support\Horarios\UtilizacionEspaciosGridBuilder;
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
            ->orderBy('id')
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
        return $this->gridBuilder()->build($this->espacioSeleccionado, $this->cursoIdsVisibles);
    }

    public function getAdvertenciasProperty(): array
    {
        return $this->gridBuilder()->warnings($this->espacioSeleccionado, $this->cursoIdsVisibles);
    }

    public function designacionTurno(string $franja): string
    {
        return match ($franja) {
            'maniana', 'tarde' => TurnoHelper::designacionTurno($franja),
            default => 'Turno',
        };
    }

    public function render()
    {
        return view('livewire.utilizacion-espacios');
    }

    private function gridBuilder(): UtilizacionEspaciosGridBuilder
    {
        return app(UtilizacionEspaciosGridBuilder::class);
    }
}
