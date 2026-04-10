<?php

namespace App\Livewire;

use Livewire\Component;
use App\Support\Dashboard\CursoAsignacionDetector;
use Illuminate\Support\Facades\Cache;

class DashboardCursosAsignacion extends Component
{
    public $estadisticas = [];
    public $cursosPendientes = [];

    public function mount()
    {
        $this->cargarEstadisticas();
    }

    public function cargarEstadisticas()
    {
        $datos = Cache::remember('dashboard.cursos_asignacion', now()->addMinutes(15), function () {
            $detector = new CursoAsignacionDetector();
            
            // Obtener todos los datos de cursos en una sola consulta
            $estadosCursos = $detector->obtenerEstadoPorCurso();
            
            // Calcular estadísticas a partir de los datos obtenidos
            $cursosSinMaterias = $estadosCursos->filter(fn($c) => $c['estado'] === 'sin_materias')->count();
            $cursosSinHorarios = $estadosCursos->filter(fn($c) => $c['estado'] === 'sin_horarios')->count();
            
            $estadisticas = [
                'total_cursos' => $estadosCursos->count(),
                'cursos_sin_materias' => $cursosSinMaterias,
                'cursos_sin_horarios' => $cursosSinHorarios,
                'total_conflictos' => $cursosSinMaterias + $cursosSinHorarios,
                'cursos_completos' => $estadosCursos->count() - $cursosSinMaterias - $cursosSinHorarios,
            ];
            
            $cursosPendientes = $estadosCursos
                ->filter(fn($c) => in_array($c['estado'], ['sin_materias', 'sin_horarios']))
                ->take(5)
                ->toArray();
                
            return [
                'estadisticas' => $estadisticas,
                'cursosPendientes' => $cursosPendientes,
            ];
        });

        $this->estadisticas = $datos['estadisticas'];
        $this->cursosPendientes = $datos['cursosPendientes'];
    }

    public function render()
    {
        return view('livewire.dashboard-cursos-asignacion', [
            'estadisticas' => $this->estadisticas,
            'cursosPendientes' => $this->cursosPendientes,
        ]);
    }
}
