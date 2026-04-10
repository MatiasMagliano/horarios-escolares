<?php

namespace App\Livewire;

use App\Support\Dashboard\DocenteSuperposicionDetector;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class DashboardSuperposicionesDocentes extends Component
{
    public function render()
    {
        $conflictos = Cache::remember('dashboard.superposiciones', now()->addMinutes(15), function () {
            return app(DocenteSuperposicionDetector::class)->detect();
        });

        return view('livewire.dashboard-superposiciones-docentes', [
            'conflictos' => $conflictos,
        ]);
    }
}
