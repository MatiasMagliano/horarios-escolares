<?php

namespace App\Livewire;

use App\Models\Curso;
use Livewire\Component;
use Illuminate\Support\Facades\Request;

class CursosPanel extends Component
{
    public string $vista = 'listado';
    public ?int $cursoIdSeleccionado = null;

    public function mount(string $vista = 'listado'): void
    {
        $this->vista = in_array($vista, ['listado', 'materias'], true)
            ? $vista
            : 'listado';

        // Detectar si viene un parámetro de curso en la URL
        $cursoIdDesdeUrl = Request::query('curso');
        if ($cursoIdDesdeUrl && is_numeric($cursoIdDesdeUrl)) {
            $this->cursoIdSeleccionado = (int) $cursoIdDesdeUrl;
        }
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
