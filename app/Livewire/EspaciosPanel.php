<?php

namespace App\Livewire;

use Livewire\Component;

class EspaciosPanel extends Component
{
    public string $vista = 'utilizacion';

    public function mostrarUtilizacion(): void
    {
        $this->vista = 'utilizacion';
    }

    public function mostrarAdministracion(): void
    {
        $this->vista = 'administracion';
    }

    public function render()
    {
        return view('livewire.espacios-panel');
    }
}
