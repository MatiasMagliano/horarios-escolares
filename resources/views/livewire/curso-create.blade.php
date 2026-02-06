<?php

use Livewire\Component;
use App\Models\Curso;

new class extends Component
{
    public ?int $anio = null;
    public ?string $turno = null;

    public function getCicloProperty(): ?string
    {
        if (!$this->anio) {
            return null;
        }

        return $this->anio <= 3 ? 'CB' : 'CE';
    }

    public function getDivisionSugeridaProperty(): ?string
    {
        if (!$this->anio || !$this->turno) {
            return null;
        }

        $existentes = Curso::where('anio', $this->anio)
            ->where('turno', $this->turno)
            ->pluck('division')
            ->map(fn ($d) => ord($d))
            ->toArray();

        if (empty($existentes)) {
            return 'A';
        }

        return chr(max($existentes) + 1);
    }

    public function guardar()
    {
        $this->validate([
            'anio'  => 'required|integer|between:1,7',
            'turno' => 'required|in:maniana,tarde',
        ]);

        if (!$this->divisionSugerida) {
            $this->addError('division', 'No se pudo determinar la división.');
            return;
        }

        Curso::create([
            'anio'     => $this->anio,
            'division' => $this->divisionSugerida,
            'turno'    => $this->turno,
            'ciclo'    => $this->ciclo,
        ]);

        session()->flash('success', 'Curso creado correctamente.');

        $this->reset();
    }
};
?>

<div>
    <div class="card">
        <div class="card-header fw-bold">
            Alta de Curso
        </div>

        <div class="card-body">
            <form wire:submit.prevent="guardar">

                {{-- AÑO --}}
                <div class="mb-3">
                    <label class="form-label">Año</label>
                    <select wire:model.live="anio" class="form-select">
                        <option value="">Seleccione...</option>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">{{ $i }}º</option>
                        @endfor
                    </select>
                    @error('anio') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- TURNO --}}
                <div class="mb-3">
                    <label class="form-label">Turno</label>
                    <select wire:model.live="turno" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="maniana">Mañana</option>
                        <option value="tarde">Tarde</option>
                    </select>
                    @error('turno') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- DIVISIÓN (SUGERIDA) --}}
                <div class="mb-3">
                    <label class="form-label">División</label>
                    <input type="text"
                        class="form-control"
                        value="{{ $divisionSugerida ?? '—' }}"
                        disabled>
                </div>

                {{-- CICLO --}}
                <div class="mb-3">
                    <label class="form-label">Ciclo</label>
                    <input type="text"
                        class="form-control"
                        value="{{ $ciclo ?? '—' }}"
                        disabled>
                </div>

                <button class="btn btn-primary">
                    Guardar Curso
                </button>
            </form>
        </div>
    </div>

</div>