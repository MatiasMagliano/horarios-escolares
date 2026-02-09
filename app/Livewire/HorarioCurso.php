<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;

class HorarioCurso extends Component
{
    public $cursoId = null;
    public $turnoVista = 'maniana';

    public function mount($cursoId = null)
    {
        $this->cursoId = $cursoId;
    }

    public function updatedCursoId()
    {
        // opcional: resetear turno si hiciera falta
    }

    public function getCursosProperty()
    {
        return Curso::orderBy('anio')->orderBy('division')->get();
    }

    public function getGrillasProperty()
    {
        if (!$this->cursoId) {
            return collect();
        }

        $curso = Curso::findOrFail($this->cursoId);
        $turnos = [$curso->turno, $this->contraturnoDe($curso->turno)];

        // Helper methods simplify the main logic and help type inference
        $bloques = $this->getBloquesForTurnos($turnos);
        $horarios = $this->getHorariosForCursoAndTurnos($this->cursoId, $turnos);

        return collect($turnos)
            ->mapWithKeys(function ($turno) use ($bloques, $horarios) {
                $bloquesDelTurno = $bloques->get($turno, collect());

                $grilla = $bloquesDelTurno->mapWithKeys(function ($bloque) use ($horarios, $turno) {
                    return [
                        $bloque->orden => collect([
                            'bloque' => $bloque,
                            'dias' => $horarios->get($turno)?->get($bloque->orden) ?? collect(),
                        ])
                    ];
                });

                return [$turno => $grilla];
            });
    }

    private function getBloquesForTurnos(array $turnos)
    {
        return BloqueHorario::whereIn('turno', $turnos)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');
    }

    private function getHorariosForCursoAndTurnos($cursoId, array $turnos)
    {
        return HorarioBase::with(['cursoMateria.materia', 'cursoMateria.docente', 'bloque'])
            ->where('curso_id', $cursoId)
            ->whereHas(
                'bloque',
                fn($q) => $q->whereIn('turno', $turnos)
            )
            ->get()
            ->groupBy(fn($h) => $h->bloque->turno)
            ->map(
                fn($items) =>
                $items->groupBy(fn($h) => $h->bloque->orden)
                    ->map(fn($i) => $i->keyBy('dia_semana'))
            );
    }

    public function render()
    {
        return view('livewire.horario-curso');
    }

    protected function contraturnoDe(string $turnoCurso): string
    {
        return match ($turnoCurso) {
            'maniana' => 'contraturno_maniana',
            'tarde' => 'contraturno_tarde',
            default => throw new \LogicException("Turno inválido: $turnoCurso"),
        };
    }

    // accesor designacion de turno
    public function designacionTurno(string $turno): string
    {
        return match ($turno) {
            'maniana' => 'Mañana',
            'tarde' => 'Tarde',
            'contraturno_maniana' => 'Contraturno Mañana',
            'contraturno_tarde' => 'Contraturno Tarde',
            default => 'Contraturno',
        };
    }

    public function getAdvertenciasProperty()
    {
        if (!$this->cursoId) {
            return [];
        }

        $advertencias = [];

        // 1. Validar Carga Horaria Incompleta
        $materias = \App\Models\CursoMateria::where('curso_id', $this->cursoId)
            ->withCount('horarioBase')
            ->with('materia')
            ->get();

        foreach ($materias as $km) {
            if ($km->horario_base_count < $km->horas_totales) {
                $faltantes = $km->horas_totales - $km->horario_base_count;
                $advertencias[] = "Faltan asignar {$faltantes} horas de " . $km->materia->nombre;
            }
        }

        return $advertencias;
    }
}
