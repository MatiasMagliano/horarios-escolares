<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;
use App\Models\CursoMateria;
use Illuminate\Support\Carbon;

class HorarioCurso extends Component
{
    protected const FECHA_VIGENCIA_INICIAL = '2026-01-01';

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

        $curso = $this->cursoSeleccionado;
        if (!$curso) {
            return collect();
        }

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
            ->vigente()
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
        ])
            ->vigente()
            ->first();

        $this->cursoMateriaSeleccionada = $horarioExistente?->curso_materia_id;

        $this->dispatch('abrir-modal-editar-celda');
    }

    public function guardarCelda()
    {
        if (!$this->celdaSeleccionada || !$this->cursoId) {
            return;
        }

        $bloqueId = (int) $this->celdaSeleccionada['bloque_id'];
        $dia = (int) $this->celdaSeleccionada['dia'];
        $vigenteDesde = $this->fechaVigencia();

        $queryCelda = HorarioBase::query()
            ->where('curso_id', $this->cursoId)
            ->where('bloque_id', $bloqueId)
            ->where('dia_semana', $dia);

        $horarioVigente = (clone $queryCelda)
            ->vigente()
            ->first();

        // Reusar la versión del día para evitar colisiones con el unique SCD2.
        $versionHoy = (clone $queryCelda)
            ->where('vigente_desde', $vigenteDesde)
            ->first();

        $cursoMateriaId = $this->cursoMateriaSeleccionada
            ? (int) $this->cursoMateriaSeleccionada
            : null;

        if ($cursoMateriaId !== null) {
            $materiaEsValida = CursoMateria::whereKey($cursoMateriaId)
                ->where('curso_id', $this->cursoId)
                ->vigente()
                ->exists();

            if (!$materiaEsValida) {
                return;
            }
        }

        if ($cursoMateriaId === null) {
            if ($horarioVigente) {
                $horarioVigente->update([
                    'es_vigente' => false,
                    'vigente_hasta' => $vigenteDesde,
                    'cambio_horario_id' => null,
                ]);
            }
        } else {
            if ($horarioVigente && (int) $horarioVigente->curso_materia_id === $cursoMateriaId) {
                $this->dispatch('cerrar-modal-editar-celda');
                return;
            }

            if ($versionHoy) {
                $versionHoy->update([
                    'curso_materia_id' => $cursoMateriaId,
                    'vigente_hasta' => null,
                    'es_vigente' => true,
                    'cambio_horario_id' => null,
                ]);

                if ($horarioVigente && $horarioVigente->id !== $versionHoy->id) {
                    $horarioVigente->update([
                        'es_vigente' => false,
                        'vigente_hasta' => Carbon::parse($vigenteDesde)->subDay()->toDateString(),
                        'cambio_horario_id' => null,
                    ]);
                }
            } else {
                if ($horarioVigente) {
                    $horarioVigente->update([
                        'es_vigente' => false,
                        'vigente_hasta' => Carbon::parse($vigenteDesde)->subDay()->toDateString(),
                        'cambio_horario_id' => null,
                    ]);
                }

                HorarioBase::create([
                    'curso_id' => $this->cursoId,
                    'bloque_id' => $bloqueId,
                    'dia_semana' => $dia,
                    'curso_materia_id' => $cursoMateriaId,
                    'vigente_desde' => $vigenteDesde,
                    'vigente_hasta' => null,
                    'es_vigente' => true,
                    'cambio_horario_id' => null,
                ]);
            }
        }

        $this->dispatch('cerrar-modal-editar-celda');
    }

    // HELPER EDICIÓN DE CELDAS
    public function getCursoMateriasProperty()
    {
        if (!$this->cursoId) {
            return collect();
        }

        return $this->cursoMateriasConCarga
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
        $materias = $this->cursoMateriasConCarga;

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

    public function getCursoSeleccionadoProperty()
    {
        if (!$this->cursoId) {
            return null;
        }

        return Curso::query()
            ->select(['id', 'turno'])
            ->find($this->cursoId);
    }

    public function getCursoMateriasConCargaProperty()
    {
        if (!$this->cursoId) {
            return collect();
        }

        return CursoMateria::query()
            ->where('curso_id', $this->cursoId)
            ->vigente()
            ->withCount([
                'horarioBase as horario_base_count' => function ($query) {
                    $query->vigente();
                }
            ])
            ->with(['materia', 'docente'])
            ->get();
    }

    private function fechaVigencia(): string
    {
        $hoy = Carbon::today();
        $inicio = Carbon::parse(static::FECHA_VIGENCIA_INICIAL);

        return $hoy->lessThan($inicio) ? $inicio->toDateString() : $hoy->toDateString();
    }

    public function render()
    {
        return view('livewire.horario-curso');
    }
}
