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
    public string $divisionSugerida = '';

    public bool $editing = false;

    public function mount(?Curso $curso = null)
    {
        if ($curso && $curso->exists) {
            $this->curso = $curso;
            $this->editing = true;

            $this->anio = $curso->anio;
            $this->division = $curso->division;
            $this->turno = $curso->turno;
            $this->divisionSugerida = $curso->division;
            return;
        }

        $this->turno = 'maniana';
        $this->actualizarDivisionSugerida();
    }

    protected function rules()
    {
        return [
            'anio' => ['required', 'integer', 'between:1,7'],

            'division' => [
                'required',
                'string',
                'max:5',
                'regex:/^[A-Za-z]{1,5}$/',
                Rule::unique('cursos')
                    ->where(fn($q) => $q->where('anio', $this->anio))
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

    protected function messages()
    {
        return [
            'division.regex' => 'La división debe contener solo letras.',
        ];
    }

    protected function calcularCiclo(): string
    {
        return $this->anio <= 3 ? 'CB' : 'CE';
    }

    public function updatedAnio(): void
    {
        if ($this->editing) {
            return;
        }

        $this->actualizarDivisionSugerida();
        $this->division = $this->divisionSugerida;
    }

    public function sugerirDivision(): void
    {
        if ($this->editing) {
            return;
        }

        $this->actualizarDivisionSugerida();
        $this->division = $this->divisionSugerida;
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
            $this->turno = 'maniana';
            $this->actualizarDivisionSugerida();
            $this->division = $this->divisionSugerida;
        }

        $this->dispatch('curso-guardado');
        $this->dispatch('curso-guardado-y-cerrar');
    }

    private function actualizarDivisionSugerida(): void
    {
        $this->divisionSugerida = $this->siguienteDivisionDisponible($this->anio);
    }

    private function siguienteDivisionDisponible(int $anio): string
    {
        $divisionesOcupadas = Curso::query()
            ->where('anio', $anio)
            ->pluck('division')
            ->map(fn($division) => strtoupper(trim($division)))
            ->filter()
            ->all();

        $indice = 0;

        do {
            $division = $this->divisionDesdeIndice($indice);
            $indice++;
        } while (in_array($division, $divisionesOcupadas, true));

        return $division;
    }

    private function divisionDesdeIndice(int $indice): string
    {
        $division = '';
        $numero = $indice;

        do {
            $division = chr(65 + ($numero % 26)) . $division;
            $numero = intdiv($numero, 26) - 1;
        } while ($numero >= 0);

        return $division;
    }
};
?>

<div class="card shadow-sm">
    <div class="card-body">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h5 class="mb-1">{{ $editing ? 'Editar división' : 'Nueva división de curso' }}</h5>
                <p class="text-muted mb-0">
                    {{ $editing
                        ? 'Ajustá los datos de la división seleccionada.'
                        : 'Creá una división nueva para un año existente entre 1º y 7º.' }}
                </p>
            </div>

            <span class="badge {{ $this->calcularCiclo() === 'CB' ? 'bg-secondary' : 'bg-info' }}">
                {{ $this->calcularCiclo() }}
            </span>
        </div>

        <form wire:submit.prevent="save">
            <div class="row">
                <div class="col-md-6 mb-3">
                    {{-- AÑO --}}
                    <div class="mb-3">
                        <label class="form-label">Año</label>
                        <select wire:model.live="anio" class="form-select">
                            @for ($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}">{{ $i }}º</option>
                            @endfor
                        </select>
                        @error('anio') <div class="text-danger small">{{ $message }}</div> @enderror
                        @unless($editing)
                        <div class="form-text">Solo se pueden crear divisiones entre 1º y 7º año.</div>
                        @endunless
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    {{-- TURNO --}}
                    <div class="mb-3">
                        <label class="form-label">Turno</label>
                        <select wire:model.live="turno" class="form-select">
                            <option value="">Seleccione turno...</option>
                            <option value="maniana">Mañana</option>
                            <option value="tarde">Tarde</option>
                        </select>
                        @error('turno') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- DIVISIÓN --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">División</label>
                    <input type="text"
                        wire:model="division"
                        class="form-control"
                        placeholder="Ej: A, B, C">
                    @error('division') <div class="text-danger small">{{ $message }}</div> @enderror
                    @unless($editing)
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">
                            Sugerida según la base: <strong>{{ $divisionSugerida }}</strong>
                        </small>
                        <button
                            type="button"
                            wire:click="sugerirDivision"
                            class="btn btn-sm btn-outline-secondary">
                            Usar sugerida
                        </button>
                    </div>
                    @endunless
                </div>

                {{-- CICLO (solo informativo) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ciclo</label>
                    <input type="text"
                        class="form-control"
                        value="{{ $this->calcularCiclo() === 'CB' ? 'CB (Ciclo Básico)' : 'CE (Ciclo de Especialización)' }}"
                        disabled>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    {{ $editing ? 'Guardar cambios' : 'Crear división' }}
                </button>
            </div>
        </form>
    </div>
</div>
