<?php

use Livewire\Component;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\CursoMateria;
use Illuminate\Validation\Rule;

new class extends Component {

    public Curso $curso;

    public $materia_id;
    public $docente_id;
    public $horas_totales;

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

        CursoMateria::create([
            'curso_id' => $this->curso->id,
            'materia_id' => $this->materia_id,
            'docente_id' => $this->docente_id,
            'horas_totales' => $this->horas_totales,
        ]);

        $this->reset(['materia_id','docente_id','horas_totales']);
        $this->dispatch('curso-materias-actualizadas');
    }

    public function eliminar($id)
    {
        $cm = CursoMateria::withCount('horarioBase')->findOrFail($id);

        if ($cm->horario_base_count > 0) return;

        $cm->delete();
    }

    public function render()
    {
        return view('livewire.curso-materias', [
            'materiasCurso' => $this->curso
                ->cursoMaterias()
                ->with(['materia','docente'])
                ->withCount('horarioBase')
                ->get(),
            'materias' => Materia::orderBy('horas_totales','desc')->get(),
            'docentes' => Docente::orderBy('nombre')->get(),
        ]);
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
        </div>

        <div class="col-md-4">
            <select wire:model="docente_id" class="form-select">
                <option value="">Docente...</option>
                @foreach($docentes as $d)
                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" wire:model="horas_totales" class="form-control" placeholder="Hs">
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
                <th>Hs</th>
                <th>Horarios</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        @foreach($materiasCurso as $cm)
            <tr>
                <td>{{ $cm->materia->nombre }}</td>
                <td>{{ $cm->docente->nombre }}</td>
                <td class="text-center">{{ $cm->horas_totales }}</td>
                <td class="text-center">
                    {{ $cm->horario_base_count }}
                </td>
                <td class="text-center">
                    <button
                        wire:click="eliminar({{ $cm->id }})"
                        class="btn btn-sm btn-outline-danger"
                        @disabled($cm->horario_base_count > 0)
                    >
                        🗑
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>