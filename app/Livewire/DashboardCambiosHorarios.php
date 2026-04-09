<?php

namespace App\Livewire;

use Livewire\Component;
use App\Support\Dashboard\CambioHorarioPendientesDetector;
use App\Models\CambioHorario;

class DashboardCambiosHorarios extends Component
{
    public $estadisticas = [];
    public $cambiosPendientes = [];

    public function mount()
    {
        $this->cargarEstadisticas();
    }

    public function cargarEstadisticas()
    {
        $detector = new CambioHorarioPendientesDetector();
        $this->estadisticas = $detector->detectarPorEstado();
        
        // Hacer una sola consulta para los cambios pendientes con select limitado
        $this->cambiosPendientes = CambioHorario::query()
            ->whereIn('estado', ['borrador', 'autorizado', 'firmado'])
            ->select('id', 'docente_id', 'curso_id', 'materia_id', 'estado', 'created_at')
            ->with([
                'docente:id,nombre_completo',
                'curso:id,anio,division,turno',
                'materia:id,nombre'
            ])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function obtenerColorEstado(string $estado): string
    {
        $detector = new CambioHorarioPendientesDetector();
        return $detector->estadoColor($estado);
    }

    public function obtenerTextoEstado(string $estado): string
    {
        $detector = new CambioHorarioPendientesDetector();
        return $detector->estadoTexto($estado);
    }

    public function render()
    {
        return view('livewire.dashboard-cambios-horarios', [
            'estadisticas' => $this->estadisticas,
            'cambiosPendientes' => $this->cambiosPendientes,
        ]);
    }
}
