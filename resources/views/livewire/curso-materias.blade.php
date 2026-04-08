<?php

use Livewire\Component;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\CmDocente;
use App\Models\CursoMateria;
use App\Models\EspacioFisico;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

new class extends Component {
    protected const FECHA_VIGENCIA_INICIAL = '2026-01-01';

    protected $listeners = [
        'espacios-fisicos-actualizados' => '$refresh',
    ];

    public Curso $curso;

    public $materia_id;
    public $docente_id;
    public $horas_totales;
    public $espacio_fisico_id;
    public ?string $mensajeError = null;

    public ?int $editandoId = null;
    public $docente_id_edicion;
    public $horas_totales_edicion;
    public $espacio_fisico_id_edicion;

    public function mount(Curso $curso)
    {
        $this->curso = $curso;
    }

    public function updatedMateriaId($value): void
    {
        if (!$value) {
            $this->espacio_fisico_id = null;
            return;
        }

        $cursoMateriaExistente = CursoMateria::query()
            ->where('curso_id', $this->curso->id)
            ->where('materia_id', $value)
            ->first();

        $this->espacio_fisico_id = $cursoMateriaExistente?->espacio_fisico_id;
    }

    public function getMateriaSeleccionadaProperty()
    {
        if (!$this->materia_id) {
            return null;
        }

        return Materia::find($this->materia_id);
    }

    protected function rules()
    {
        return [
            'materia_id' => [
                'required',
                'exists:materias,id',
                Rule::unique('curso_materia')
                    ->where(
                        fn($q) =>
                        $q->where('curso_id', $this->curso->id)
                    )
            ],
            'docente_id' => 'required|exists:docentes,id',
            'horas_totales' => 'required|integer|min:1',
            'espacio_fisico_id' => 'required|exists:espacios_fisicos,id',
        ];
    }

    public function guardar()
    {
        $this->mensajeError = null;
        $this->validate();

        DB::transaction(function () {
            $espacio = EspacioFisico::findOrFail((int) $this->espacio_fisico_id);

            $cm = CursoMateria::create([
                'curso_id' => $this->curso->id,
                'materia_id' => $this->materia_id,
                'horas_totales' => $this->horas_totales,
                'espacio_fisico_id' => $espacio->id,
            ]);

            $this->asignarDocenteVersionado($cm, (int) $this->docente_id);
        });

        $this->reset(['materia_id', 'docente_id', 'horas_totales', 'espacio_fisico_id']);
        $this->dispatch('curso-materias-actualizadas');
    }

    public function editar($id)
    {
        $this->mensajeError = null;
        $cm = CursoMateria::where('curso_id', $this->curso->id)
            ->with('cmDocenteVigente')
            ->findOrFail($id);

        $this->editandoId = $cm->id;
        $this->docente_id_edicion = $cm->cmDocenteVigente?->docente_id;
        $this->horas_totales_edicion = $cm->horas_totales;
        $this->espacio_fisico_id_edicion = $cm->espacio_fisico_id;

        $this->dispatch('abrir-modal-editar-materia');
    }

    public function cancelarEdicion()
    {
        $this->reset([
            'editandoId',
            'docente_id_edicion',
            'horas_totales_edicion',
            'espacio_fisico_id_edicion',
        ]);

        $this->dispatch('cerrar-modal-editar-materia');
    }

    public function actualizar()
    {
        $this->mensajeError = null;
        $this->validate([
            'docente_id_edicion' => 'required|exists:docentes,id',
            'horas_totales_edicion' => 'required|integer|min:1',
            'espacio_fisico_id_edicion' => 'required|exists:espacios_fisicos,id',
        ]);

        if (!$this->editandoId) {
            return;
        }

        $cm = CursoMateria::query()
            ->where('curso_id', $this->curso->id)
            ->with('cmDocenteVigente')
            ->findOrFail($this->editandoId);

        $docenteNuevoId = (int) $this->docente_id_edicion;
        $docenteActualId = $cm->cmDocenteVigente?->docente_id;
        $espacio = EspacioFisico::findOrFail((int) $this->espacio_fisico_id_edicion);

        DB::transaction(function () use ($cm, $docenteNuevoId, $docenteActualId, $espacio) {
            $cm->update([
                'horas_totales' => $this->horas_totales_edicion,
                'espacio_fisico_id' => $espacio->id,
            ]);

            if ($docenteActualId !== $docenteNuevoId) {
                $this->asignarDocenteVersionado($cm, $docenteNuevoId);
            }
        });

        $this->cancelarEdicion();
        $this->dispatch('curso-materias-actualizadas');
    }

    public function eliminar($id)
    {
        $this->mensajeError = null;

        $cm = CursoMateria::where('curso_id', $this->curso->id)
            ->withCount([
                'horarioBase as horario_base_count' => function ($query) {
                    $query->vigente();
                }
            ])
            ->findOrFail($id);

        if ($cm->horario_base_count > 0) {
            $this->mensajeError = 'No se puede eliminar la materia porque tiene módulos cargados en la grilla horaria.';
            return;
        }

        $cm->delete();
        $this->dispatch('curso-materias-actualizadas');
    }

    public function render()
    {
        $docentesAsignados = $this->curso
            ->cursoMaterias()
            ->with('cmDocenteVigente')
            ->get()
            ->pluck('cmDocenteVigente.docente_id')
            ->filter()
            ->values();

        return view('livewire.curso-materias', [
            'materiasCurso' => $this->curso
                ->cursoMaterias()
                ->with(['materia', 'cmDocenteVigente.docente', 'espacioFisico'])
                ->withCount([
                    'horarioBase as horario_base_count' => function ($query) {
                        $query->vigente();
                    }
                ])
                ->orderBy('materia_id')
                ->get(),
            'materias' => Materia::orderBy('nombre')->get(),
            'docentes' => Docente::query()
                ->where('activo', true)
                ->orWhereIn('id', $docentesAsignados)
                ->orderBy('nombre')
                ->get(),
            'espaciosFisicos' => EspacioFisico::query()
                ->where('activo', true)
                ->orderBy('id')
                ->get(),
        ]);
    }

    private function asignarDocenteVersionado(CursoMateria $cm, int $docenteId): void
    {
        $vigenteDesde = $this->fechaVigencia();

        $actual = $cm->cmDocentes()->vigente()->first();
        $versionHoy = $cm->cmDocentes()->where('vigente_desde', $vigenteDesde)->first();

        if ($versionHoy) {
            $versionHoy->update([
                'docente_id' => $docenteId,
                'vigente_hasta' => null,
                'es_vigente' => true,
            ]);

            if ($actual && $actual->id !== $versionHoy->id) {
                $actual->update([
                    'es_vigente' => false,
                    'vigente_hasta' => Carbon::parse($vigenteDesde)->subDay()->toDateString(),
                ]);
            }
            return;
        }

        if ($actual) {
            $actual->update([
                'es_vigente' => false,
                'vigente_hasta' => Carbon::parse($vigenteDesde)->subDay()->toDateString(),
            ]);
        }

        $cm->cmDocentes()->create([
            'docente_id' => $docenteId,
            'vigente_desde' => $vigenteDesde,
            'vigente_hasta' => null,
            'es_vigente' => true,
        ]);
    }

    private function fechaVigencia(): string
    {
        $hoy = Carbon::today();
        $inicio = Carbon::parse(static::FECHA_VIGENCIA_INICIAL);

        return $hoy->lessThan($inicio) ? $inicio->toDateString() : $hoy->toDateString();
    }
};
?>

<div>
    @if($mensajeError)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ $mensajeError }}
        <button type="button" class="btn-close" wire:click="$set('mensajeError', null)"></button>
    </div>
    @endif

    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-1">Agregar materia al curso</h5>
                    <p class="text-muted small mb-0">
                        Cargá la materia, el docente responsable y la carga horaria semanal.
                    </p>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Materia</label>
                        <select wire:model.live="materia_id" class="form-select">
                            <option value="">Seleccione una materia...</option>
                            @foreach($materias as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                        @error('materia_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select wire:model="docente_id" class="form-select">
                            <option value="">Seleccione un docente...</option>
                            @foreach($docentes as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                        @error('docente_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Carga horaria</label>
                        <input type="number" wire:model="horas_totales" class="form-control" placeholder="Cantidad de horas">
                        @error('horas_totales') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Espacio físico</label>
                        <select wire:model="espacio_fisico_id" class="form-select" @disabled(!$materia_id)>
                            <option value="">Seleccione un espacio...</option>
                            @foreach($espaciosFisicos as $espacio)
                            <option value="{{ $espacio->id }}">{{ $espacio->nombre }}</option>
                            @endforeach
                        </select>
                        @error('espacio_fisico_id') <small class="text-danger">{{ $message }}</small> @enderror
                        <div class="form-text">
                            Se guarda el espacio físico puntual asignado a esta materia dentro del curso.
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <button type="button" wire:click="guardar" class="btn btn-primary w-100">
                        Agregar materia
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Materias asignadas</h5>
                        <p class="text-muted small mb-0">
                            Administrá docentes y verificá la carga horaria ya distribuida.
                        </p>
                    </div>
                    <span class="badge bg-secondary">{{ $materiasCurso->count() }} registradas</span>
                </div>

                <div class="card-body p-0">
                    @if($materiasCurso->isEmpty())
                    <div class="p-4 text-muted">
                        Este curso todavía no tiene materias cargadas.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Materia</th>
                                    <th>Espacio</th>
                                    <th>Docente</th>
                                    <th>Carga horaria</th>
                                    <th>Hs cargadas</th>
                                    <th style="width: 12%;">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($materiasCurso as $cm)
                                <tr>
                                    <td class="fw-semibold">{{ $cm->materia->nombre }}</td>
                                    <td class="text-center">
                                        <span class="badge text-bg-light border">
                                            {{ $cm->espacioFisico?->nombre ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $cm->cmDocenteVigente?->docente?->nombre ?? '—' }}</td>
                                    <td class="text-center">{{ $cm->horas_totales }}</td>
                                    <td class="text-center">
                                        <span class="fw-semibold">{{ $cm->horario_base_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            wire:click="editar({{ $cm->id }})"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="eliminar({{ $cm->id }})"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminación no disponible si la materia tiene horarios cargados"
                                            >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editarMateriaCursoModal-{{ $this->getId() }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar materia del curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelarEdicion"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Docente</label>
                            <select wire:model="docente_id_edicion" class="form-select">
                                <option value="">Seleccione un docente...</option>
                                @foreach($docentes as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                @endforeach
                            </select>
                            @error('docente_id_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Carga horaria</label>
                            <input type="number" wire:model="horas_totales_edicion" class="form-control" placeholder="Cantidad de horas">
                            @error('horas_totales_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label class="form-label">Espacio físico</label>
                            <select wire:model="espacio_fisico_id_edicion" class="form-select">
                                <option value="">Seleccione un espacio...</option>
                                @foreach($espaciosFisicos as $espacio)
                                <option value="{{ $espacio->id }}">{{ $espacio->nombre }}</option>
                                @endforeach
                            </select>
                            @error('espacio_fisico_id_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="alert alert-secondary mb-0">
                        El cambio de docente se versiona con SCD2. La carga horaria y el espacio se actualizan sobre los registros actuales.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="cancelarEdicion">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="actualizar">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const editarMateriaCursoModalEl = document.getElementById('editarMateriaCursoModal-{{ $this->getId() }}');

    $wire.on('abrir-modal-editar-materia', () => {
        if (!editarMateriaCursoModalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(editarMateriaCursoModalEl).show();
    });

    $wire.on('cerrar-modal-editar-materia', () => {
        if (!editarMateriaCursoModalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(editarMateriaCursoModalEl).hide();
    });
</script>
@endscript
