<?php

namespace App\Livewire;

use Livewire\Component;
use App\Support\Dashboard\CursoAsignacionDetector;

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
        $detector = new CursoAsignacionDetector();
        
        // Obtener todos los datos de cursos en una sola consulta
        $estadosCursos = $detector->obtenerEstadoPorCurso();
        
        // Calcular estadísticas a partir de los datos obtenidos
        $cursosSinMaterias = $estadosCursos->filter(fn($c) => $c['estado'] === 'sin_materias')->count();
        $cursosSinHorarios = $estadosCursos->filter(fn($c) => $c['estado'] === 'sin_horarios')->count();
        
        $this->estadisticas = [
            'total_cursos' => $estadosCursos->count(),
            'cursos_sin_materias' => $cursosSinMaterias,
            'cursos_sin_horarios' => $cursosSinHorarios,
            'total_conflictos' => $cursosSinMaterias + $cursosSinHorarios,
            'cursos_completos' => $estadosCursos->count() - $cursosSinMaterias - $cursosSinHorarios,
        ];
        
        $this->cursosPendientes = $estadosCursos
            ->filter(fn($c) => in_array($c['estado'], ['sin_materias', 'sin_horarios']))
            ->take(5)
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard-cursos-asignacion', [
            'estadisticas' => $this->estadisticas,
            'cursosPendientes' => $this->cursosPendientes,
        ]);
    }
}
