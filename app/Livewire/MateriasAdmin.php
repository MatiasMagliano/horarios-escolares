<?php

namespace App\Livewire;

use App\Models\Materia;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class MateriasAdmin extends Component
{
    use WithPagination;

    public string $nombre = '';
    public ?int $editandoId = null;
    public string $busqueda = '';

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materias', 'nombre')->ignore($this->editandoId),
            ],
        ];
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function guardar(): void
    {
        $this->nombre = trim(mb_strtoupper($this->nombre));
        $this->validate();

        if ($this->editandoId) {
            Materia::findOrFail($this->editandoId)->update([
                'nombre' => $this->nombre,
            ]);
            session()->flash('success', 'Materia actualizada correctamente.');
        } else {
            Materia::create([
                'nombre' => $this->nombre,
            ]);
            session()->flash('success', 'Materia creada correctamente.');
        }

        $this->cancelar();
        $this->dispatch('materias-actualizadas');
    }

    public function editar(int $id): void
    {
        $materia = Materia::findOrFail($id);

        $this->editandoId = $materia->id;
        $this->nombre = $materia->nombre;

        $this->dispatch('abrir-modal-materia');
    }

    public function nuevo(): void
    {
        $this->resetFormulario();
        $this->dispatch('abrir-modal-materia');
    }

    public function cancelar(): void
    {
        $this->resetFormulario();
        $this->dispatch('cerrar-modal-materia');
    }

    private function resetFormulario(): void
    {
        $this->reset(['editandoId', 'nombre']);
    }

    public function render()
    {
        $query = Materia::query()
            ->withCount('cursoMaterias');

        if (!empty($this->busqueda)) {
            $query->where('nombre', 'like', '%' . $this->busqueda . '%');
        }

        return view('livewire.materias-admin', [
            'materias' => $query->orderBy('nombre')->paginate(15),
        ]);
    }
}
