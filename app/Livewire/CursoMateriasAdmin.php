<?php

namespace App\Livewire;

use App\Models\Curso;
use Livewire\Component;

class CursoMateriasAdmin extends Component
{
    public Curso $curso;

    public function mount(Curso $curso): void
    {
        $this->curso = $curso;
    }

    public function render()
    {
        return view('livewire.curso-materias-admin');
    }
}
