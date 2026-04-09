<?php

namespace App\Support\Dashboard;

use App\Models\Curso;
use Illuminate\Support\Collection;

class CursoAsignacionDetector
{
    public function detectarProblemas(): array
    {
        $cursos = Curso::query()
            ->withCount('cursoMaterias')
            ->withCount(['horariosBase' => fn($q) => $q->vigente()])
            ->get();

        $cursosSinMaterias = 0;
        $cursosSinHorarios = 0;

        foreach ($cursos as $c) {
            if ($c->curso_materias_count === 0) {
                $cursosSinMaterias++;
            } elseif ($c->horarios_base_count === 0) {
                $cursosSinHorarios++;
            }
        }

        return [
            'total_cursos' => $cursos->count(),
            'cursos_sin_materias' => $cursosSinMaterias,
            'cursos_sin_horarios' => $cursosSinHorarios,
            'total_conflictos' => $cursosSinMaterias + $cursosSinHorarios,
            'cursos_completos' => $cursos->count() - $cursosSinMaterias - $cursosSinHorarios,
        ];
    }

    public function obtenerCursosSinMaterias(): Collection
    {
        return Curso::query()
            ->withCount('cursoMaterias')
            ->having('curso_materias_count', '=', 0)
            ->orderBy('anio')
            ->orderBy('division')
            ->get();
    }

    public function obtenerCursosSinHorarios(): Collection
    {
        return Curso::query()
            ->withCount('cursoMaterias')
            ->withCount(['horariosBase' => fn($q) => $q->vigente()])
            ->havingRaw('curso_materias_count > 0 AND horarios_base_count = 0')
            ->orderBy('anio')
            ->orderBy('division')
            ->get();
    }

    public function obtenerEstadoPorCurso(): Collection
    {
        return Curso::query()
            ->withCount('cursoMaterias')
            ->withCount(['horariosBase' => fn($q) => $q->vigente()])
            ->orderBy('anio')
            ->orderBy('division')
            ->get()
            ->map(function($curso) {
                $sinMaterias = $curso->curso_materias_count === 0;
                $sinHorarios = $curso->curso_materias_count > 0 && $curso->horarios_base_count === 0;

                return [
                    'id' => $curso->id,
                    'nombre_completo' => $curso->nombre_completo,
                    'anio' => $curso->anio,
                    'division' => $curso->division,
                    'materias_count' => $curso->curso_materias_count,
                    'horarios_count' => $curso->horarios_base_count,
                    'estado' => $sinMaterias ? 'sin_materias' : ($sinHorarios ? 'sin_horarios' : 'completo'),
                ];
            });
    }

    public function estadoColor(string $estado): string
    {
        return match($estado) {
            'sin_materias' => 'warning',
            'sin_horarios' => 'info',
            'completo' => 'success',
            default => 'secondary',
        };
    }

    public function estadoTexto(string $estado): string
    {
        return match($estado) {
            'sin_materias' => 'Sin materias',
            'sin_horarios' => 'Sin horarios',
            'completo' => 'Completo',
            default => 'Desconocido',
        };
    }

    public function estadoIcono(string $estado): string
    {
        return match($estado) {
            'sin_materias' => 'exclamation-circle',
            'sin_horarios' => 'clock-history',
            'completo' => 'check-circle',
            default => 'question-circle',
        };
    }
}
