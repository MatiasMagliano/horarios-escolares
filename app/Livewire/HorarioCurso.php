<?php

namespace App\Livewire;

use App\Models\CursoMateria;
use App\Models\Curso;
use App\Models\HorarioBase;
use App\Support\Horarios\HorarioCursoGridBuilder;
use App\Support\Horarios\TurnoHelper;
use Illuminate\Support\Carbon;
use Livewire\Component;

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

    public function getGrillasProperty()
    {
        return $this->gridBuilder()->build($this->cursoId ? (int) $this->cursoId : null);
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

    public function designacionTurno(string $turno): string
    {
        return TurnoHelper::designacionTurno($turno);
    }

    public function getAdvertenciasProperty()
    {
        return $this->gridBuilder()->warnings($this->cursoId ? (int) $this->cursoId : null);
    }

    public function getCursoSeleccionadoProperty()
    {
        return $this->gridBuilder()->getCursoSeleccionado($this->cursoId ? (int) $this->cursoId : null);
    }

    public function getCursoMateriasConCargaProperty()
    {
        return $this->gridBuilder()->getCursoMateriasConCarga($this->cursoId ? (int) $this->cursoId : null);
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

    private function gridBuilder(): HorarioCursoGridBuilder
    {
        return app(HorarioCursoGridBuilder::class);
    }
}
