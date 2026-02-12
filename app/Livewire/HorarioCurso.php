<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;
use App\Models\CursoMateria;

class HorarioCurso extends Component
{
    protected $listeners = ['curso-materias-actualizadas' => '$refresh'];

    public $cursoId = null;
    public $turnoVista = 'maniana';
    public $celdaSeleccionada = null;
    public $cursoMateriaSeleccionada = null;


    public function mount($cursoId = null)
    {
        $this->cursoId = $cursoId;
    }

    public function getCursosProperty()
    {
        return Curso::orderBy('anio')->orderBy('division')->get();
    }

    // RECONSTRUCCIÓN DE LA GRILLA
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

    // HELPER DE getGrillasProperty
    private function getBloquesForTurnos(array $turnos)
    {
        return BloqueHorario::whereIn('turno', $turnos)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');
    }

    // HELPER DE getGrillasProperty
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

    // EDICIÓN DE CELDAS (2 funciones)
    public function editarCelda($bloqueId, $dia)
    {
        $this->celdaSeleccionada = [
            'bloque_id' => $bloqueId,
            'dia' => $dia,
        ];

        $horarioExistente = HorarioBase::where([
            'curso_id' => $this->cursoId,
            'bloque_id' => $bloqueId,
            'dia_semana' => $dia,
        ])->first();

        $this->cursoMateriaSeleccionada = $horarioExistente?->curso_materia_id;

        $this->dispatch('abrir-modal-editar-celda');
    }
    public function guardarCelda()
    {
        if (!$this->cursoMateriaSeleccionada) {
            HorarioBase::where([
                'curso_id' => $this->cursoId,
                'bloque_id' => $this->celdaSeleccionada['bloque_id'],
                'dia_semana' => $this->celdaSeleccionada['dia'],
            ])->delete();
        } else {
            HorarioBase::updateOrCreate(
                [
                    'curso_id' => $this->cursoId,
                    'bloque_id' => $this->celdaSeleccionada['bloque_id'],
                    'dia_semana' => $this->celdaSeleccionada['dia'],
                ],
                [
                    'curso_materia_id' => $this->cursoMateriaSeleccionada,
                ]
            );
        }

        $this->dispatch('cerrar-modal-editar-celda');
    }

    // HELPER EDICIÓN DE CELDAS
    public function getCursoMateriasProperty()
    {
        if (!$this->cursoId) {
            return collect();
        }

        return CursoMateria::where('curso_id', $this->cursoId)
            ->withCount('horarioBase')
            ->with(['materia', 'docente'])
            ->get()
            ->filter(function ($cm) {
                // Siempre permitir la materia actualmente seleccionada
                if ($cm->id == $this->cursoMateriaSeleccionada) {
                    return true;
                }

                // Ocultar si ya completó sus horas
                return $cm->horario_base_count < $cm->horas_totales;
            });
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
        $materias = CursoMateria::where('curso_id', $this->cursoId)
            ->withCount('horarioBase')
            ->with('materia')
            ->get();

        if ($materias->isEmpty()) {
            return ['El curso no tiene materias asignadas.'];
        }

        foreach ($materias as $km) {
            if ($km->horario_base_count < $km->horas_totales) {
                $faltantes = $km->horas_totales - $km->horario_base_count;
                $advertencias[] = "Faltan asignar {$faltantes} horas de {$km->materia->nombre}.";
            }

            if ($km->horario_base_count > $km->horas_totales) {
                $excedente = $km->horario_base_count - $km->horas_totales;
                $advertencias[] = "La materia {$km->materia->nombre} tiene {$excedente} horas de más.";
            }

            if ($km->horario_base_count == 0) {
                $advertencias[] = "La materia {$km->materia->nombre} no tiene ninguna hora asignada.";
                continue;
            }
        }
        return $advertencias;
    }

    public function render()
    {
        return view('livewire.horario-curso');
    }
}
