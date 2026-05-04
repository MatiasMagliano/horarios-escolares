<?php

namespace App\Livewire;

use App\Models\EspacioFisico;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EspaciosFisicosAdmin extends Component
{
    public string $nombre = '';
    public string $tipo = '';
    public bool $activo = true;

    public ?int $editandoId = null;
    public string $nombre_edicion = '';
    public string $tipo_edicion = '';
    public bool $activo_edicion = true;

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('espacios_fisicos', 'nombre')
                    ->where('institucion_id', auth()->user()?->institucion_activa_id),
            ],
            'tipo' => ['required', 'in:' . implode(',', EspacioFisico::tiposDisponibles())],
            'activo' => ['boolean'],
        ];
    }

    protected function rulesEdicion(): array
    {
        return [
            'nombre_edicion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('espacios_fisicos', 'nombre')
                    ->where('institucion_id', auth()->user()?->institucion_activa_id)
                    ->ignore($this->editandoId),
            ],
            'tipo_edicion' => ['required', 'in:' . implode(',', EspacioFisico::tiposDisponibles())],
            'activo_edicion' => ['boolean'],
        ];
    }

    public function guardar(): void
    {
        Gate::authorize('abm-espacios');

        $this->validate();

        EspacioFisico::create([
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'activo' => $this->activo,
        ]);

        $this->resetFormulario();
        $this->dispatch('espacios-fisicos-actualizados');
    }

    public function editar(int $id): void
    {
        Gate::authorize('abm-espacios');

        $espacio = EspacioFisico::findOrFail($id);

        $this->editandoId = $espacio->id;
        $this->nombre_edicion = $espacio->nombre;
        $this->tipo_edicion = $espacio->tipo ?? EspacioFisico::TIPO_AULA;
        $this->activo_edicion = (bool) $espacio->activo;

        $this->dispatch('abrir-modal-editar-espacio');
    }

    public function actualizar(): void
    {
        Gate::authorize('abm-espacios');

        if (!$this->editandoId) {
            return;
        }

        $this->validate($this->rulesEdicion());

        EspacioFisico::findOrFail($this->editandoId)->update([
            'nombre' => $this->nombre_edicion,
            'tipo' => $this->tipo_edicion,
            'activo' => $this->activo_edicion,
        ]);

        $this->dispatch('espacios-fisicos-actualizados');
        $this->cancelar();
    }

    public function cancelar(): void
    {
        $this->resetFormularioEdicion();
        $this->dispatch('cerrar-modal-editar-espacio');
    }

    private function resetFormularioAlta(): void
    {
        $this->reset(['nombre', 'tipo']);
        $this->activo = true;
    }

    private function resetFormularioEdicion(): void
    {
        $this->reset(['editandoId', 'nombre_edicion', 'tipo_edicion']);
        $this->activo_edicion = true;
    }

    private function resetFormulario(): void
    {
        $this->resetFormularioAlta();
        $this->resetFormularioEdicion();
    }

    public function render()
    {
        return view('livewire.espacios-fisicos-admin', [
            'espacios' => EspacioFisico::query()
                ->withCount('cursoMaterias')
                ->orderBy('nombre')
                ->get(),
            'tipos' => collect(EspacioFisico::tiposDisponibles())
                ->mapWithKeys(fn ($tipo) => [$tipo => $this->etiquetaTipo($tipo)]),
        ]);
    }

    private function etiquetaTipo(string $tipo): string
    {
        return match ($tipo) {
            EspacioFisico::TIPO_AULA => 'Aula',
            EspacioFisico::TIPO_LAB_INFORMATICA => 'Laboratorio de Informática',
            EspacioFisico::TIPO_LAB_ELECTRONICA => 'Laboratorio de Electrónica',
            EspacioFisico::TIPO_LAB_TALLER => 'Laboratorio / Taller',
            EspacioFisico::TIPO_PATIO => 'Patio',
            default => $tipo,
        };
    }
}
