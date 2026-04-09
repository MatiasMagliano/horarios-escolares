<?php

namespace App\Livewire;

use Livewire\Component;

class EspaciosPanel extends Component
{
    public string $vista = 'utilizacion';

    public function mount(string $vista = 'utilizacion'): void
    {
        $this->vista = in_array($vista, ['utilizacion', 'administracion'], true)
            ? $vista
            : 'utilizacion';
    }

    public function render()
    {
        return view('livewire.espacios-panel');
    }
}
