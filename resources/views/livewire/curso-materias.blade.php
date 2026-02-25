<?php

use Livewire\Component;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\CmDocente;
use App\Models\CursoMateria;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

new class extends Component {
    protected const FECHA_VIGENCIA_INICIAL = '2026-01-01';

    public Curso $curso;

    public $materia_id;
    public $docente_id;
    public $horas_totales;
    public ?int $editandoId = null;
    public $docente_id_edicion;

    public function mount(Curso $curso)
    {
        $this->curso = $curso;
    }

    protected function rules()
    {
        return [
            'materia_id' => [
                'required',
                'exists:materias,id',
                Rule::unique('curso_materia')
                    ->where(fn ($q) =>
                        $q->where('curso_id', $this->curso->id)
                    )
            ],
            'docente_id' => 'required|exists:docentes,id',
            'horas_totales' => 'required|integer|min:1'
        ];
    }

    public function guardar()
    {
        $this->validate();

        DB::transaction(function () {
            $cm = CursoMateria::create([
                'curso_id' => $this->curso->id,
                'materia_id' => $this->materia_id,
                'horas_totales' => $this->horas_totales,
            ]);

            $this->asignarDocenteVersionado($cm, (int) $this->docente_id);
        });

        $this->reset(['materia_id','docente_id','horas_totales']);
        $this->dispatch('curso-materias-actualizadas');
    }

    public function editar($id)
    {
        $cm = CursoMateria::where('curso_id', $this->curso->id)
            ->with('cmDocenteVigente')
            ->findOrFail($id);

        $this->editandoId = $cm->id;
        $this->docente_id_edicion = $cm->cmDocenteVigente?->docente_id;
    }

    public function cancelarEdicion()
    {
        $this->reset(['editandoId', 'docente_id_edicion']);
    }

    public function actualizar($id)
    {
        $this->validate([
            'docente_id_edicion' => 'required|exists:docentes,id',
        ]);

        $cm = CursoMateria::where('curso_id', $this->curso->id)
            ->with('cmDocenteVigente')
            ->findOrFail($id);

        $docenteNuevoId = (int) $this->docente_id_edicion;
        $docenteActualId = $cm->cmDocenteVigente?->docente_id;

        if ($docenteActualId === $docenteNuevoId) {
            $this->cancelarEdicion();
            return;
        }

        DB::transaction(function () use ($cm, $docenteNuevoId) {
            $this->asignarDocenteVersionado($cm, $docenteNuevoId);
        });

        $this->cancelarEdicion();
        $this->dispatch('curso-materias-actualizadas');
    }

    public function eliminar($id)
    {
        $cm = CursoMateria::where('curso_id', $this->curso->id)
            ->withCount([
                'horarioBase as horario_base_count' => function ($query) {
                    $query->vigente();
                }
            ])
            ->findOrFail($id);

        if ($cm->horario_base_count > 0) return;

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
                ->with(['materia', 'cmDocenteVigente.docente'])
                ->withCount([
                    'horarioBase as horario_base_count' => function ($query) {
                        $query->vigente();
                    }
                ])
                ->get(),
            'materias' => Materia::orderBy('horas_totales','desc')->get(),
            'docentes' => Docente::query()
                ->where('activo', true)
                ->orWhereIn('id', $docentesAsignados)
                ->orderBy('nombre')
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

<div class="mt-4">

    <h5 class="mb-3">Materias del Curso</h5>

    {{-- FORM INLINE --}}
    <div class="row g-2 mb-3">

        <div class="col-md-4">
            <select wire:model="materia_id" class="form-select">
                <option value="">Materia...</option>
                @foreach($materias as $m)
                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
            @error('materia_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-4">
            <select wire:model="docente_id" class="form-select">
                <option value="">Docente...</option>
                @foreach($docentes as $d)
                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                @endforeach
            </select>
            @error('docente_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-2">
            <input type="number" wire:model="horas_totales" class="form-control" placeholder="Hs">
            @error('horas_totales') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-2">
            <button type="button" wire:click="guardar" class="btn btn-primary w-100">
                Agregar
            </button>
        </div>

    </div>

    {{-- TABLA --}}
    <table class="table table-sm table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>Materia</th>
                <th>Docente</th>
                <th>Carga horaria</th>
                <th>Hs cargadas</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        @foreach($materiasCurso as $cm)
            <tr>
                <td>{{ $cm->materia->nombre }}</td>
                <td>
                    @if($editandoId === $cm->id)
                        <select wire:model="docente_id_edicion" class="form-select form-select-sm">
                            <option value="">Docente...</option>
                            @foreach($docentes as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                        @error('docente_id_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                    @else
                        {{ $cm->cmDocenteVigente?->docente?->nombre ?? '—' }}
                    @endif
                </td>
                <td class="text-center">{{ $cm->horas_totales }}</td>
                <td class="text-center">{{ $cm->horario_base_count }}</td>
                <td class="text-center">
                    @if($editandoId === $cm->id)
                        <button
                            type="button"
                            wire:click="actualizar({{ $cm->id }})"
                            class="btn btn-sm btn-outline-success"
                        >
                            <i class="bi bi-check-square"></i>
                        </button>
                        <button
                            type="button"
                            wire:click="cancelarEdicion"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="bi bi-x-square"></i>
                        </button>
                    @else
                        <button
                            type="button"
                            wire:click="editar({{ $cm->id }})"
                            class="btn btn-sm btn-outline-primary"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button
                            type="button"
                            wire:click="eliminar({{ $cm->id }})"
                            class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Eliminación no disponible si la materia tiene horarios cargados"
                            @disabled($cm->horario_base_count > 0)
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
