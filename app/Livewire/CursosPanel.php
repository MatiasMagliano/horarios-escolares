<?php

namespace App\Livewire;

use App\Models\Curso;
use Livewire\Component;

class CursosPanel extends Component
{
    public string $vista = 'listado';
    public ?int $cursoIdSeleccionado = null;

    public function mostrarListado(): void
    {
        $this->vista = 'listado';
    }

    public function mostrarMaterias(): void
    {
        $this->vista = 'materias';
    }

    public function getCursoSeleccionadoProperty(): ?Curso
    {
        if (!$this->cursoIdSeleccionado) {
            return null;
        }

        return Curso::find($this->cursoIdSeleccionado);
    }

    public function getCursosProperty()
    {
        return Curso::query()
            ->orderBy('anio')
            ->orderBy('division')
            ->get();
    }

    public function render()
    {
        return view('livewire.cursos-panel');
    }
}
