<?php

use Livewire\Component;
use App\Models\Curso;
use Illuminate\Validation\Rule;

new class extends Component
{
    public ?Curso $curso = null;

    public int $anio = 1;
    public string $division = '';
    public string $turno = '';

    public bool $editing = false;

    public function mount(?Curso $curso = null)
    {
        if ($curso && $curso->exists) {
            $this->curso = $curso;
            $this->editing = true;

            $this->anio = $curso->anio;
            $this->division = $curso->division;
            $this->turno = $curso->turno;
        }
    }

    protected function rules()
    {
        return [
            'anio' => ['required', 'integer', 'between:1,7'],

            'division' => [
                'required',
                'string',
                'max:5',
                Rule::unique('cursos')
                    ->where(fn ($q) => $q->where('anio', $this->anio))
                    ->ignore($this->curso?->id),
            ],

            'turno' => [
                'required',
                Rule::in([
                    'maniana',
                    'tarde',
                ]),
            ],
        ];
    }

    protected function calcularCiclo(): string
    {
        return $this->anio <= 3 ? 'CB' : 'CE';
    }

    public function save()
    {
        $this->validate();

        $data = [
            'anio' => $this->anio,
            'division' => strtoupper($this->division),
            'turno' => $this->turno,
            'ciclo' => $this->calcularCiclo(),
        ];

        if ($this->editing) {
            $this->curso->update($data);
            session()->flash('success', 'Curso actualizado correctamente.');
        } else {
            Curso::create($data);
            session()->flash('success', 'Curso creado correctamente.');
            $this->reset(['anio', 'division', 'turno']);
            $this->anio = 1;
        }

        $this->dispatch('curso-guardado');
    }
};
?>

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="card-title mb-3">
            {{ $editing ? 'Editar Curso' : 'Nuevo Curso' }}
        </h5>

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save">

            {{-- AÑO --}}
            <div class="mb-3">
                <label class="form-label">Año</label>
                <select wire:model="anio" class="form-select">
                    @for ($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}">{{ $i }}º</option>
                    @endfor
                </select>
                @error('anio') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- DIVISIÓN --}}
            <div class="mb-3">
                <label class="form-label">División</label>
                <input type="text"
                       wire:model="division"
                       class="form-control"
                       placeholder="Ej: A, B, C">
                @error('division') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- TURNO --}}
            <div class="mb-3">
                <label class="form-label">Turno</label>
                <select wire:model="turno" class="form-select">
                    <option value="">Seleccione turno...</option>
                    <option value="maniana">Mañana</option>
                    <option value="tarde">Tarde</option>
                </select>
                @error('turno') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- CICLO (solo informativo) --}}
            <div class="mb-3">
                <label class="form-label">Ciclo</label>
                <input type="text"
                       class="form-control"
                       value="{{ $anio <= 3 ? 'CB (Ciclo Básico)' : 'CE (Ciclo de Especialización)' }}"
                       disabled>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ $editing ? 'Actualizar' : 'Guardar' }}
            </button>

        </form>

    </div>
</div>
