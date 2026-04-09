<?php

namespace App\Livewire;

use App\Support\Dashboard\DocenteSuperposicionDetector;
use Livewire\Component;

class DashboardSuperposicionesDocentes extends Component
{
    public function render()
    {
        $conflictos = app(DocenteSuperposicionDetector::class)->detect();

        return view('livewire.dashboard-superposiciones-docentes', [
            'conflictos' => $conflictos,
        ]);
    }
}
