<?php

namespace App\Support\Dashboard;

use App\Models\Curso;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        return DB::table('cursos as c')
            ->select('c.id', 'c.anio', 'c.division', 'c.turno')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                      ->from('curso_materia as cm')
                      ->whereColumn('cm.curso_id', 'c.id');
            }, 'curso_materias_count')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                      ->from('horarios_base as hb')
                      ->whereColumn('hb.curso_id', 'c.id')
                      ->where('hb.es_vigente', true)
                      ->whereNull('hb.vigente_hasta');
            }, 'horarios_base_count')
            ->orderBy('c.anio')
            ->orderBy('c.division')
            ->get()
            ->map(function($curso) {
                $sinMaterias = $curso->curso_materias_count == 0;
                $sinHorarios = $curso->curso_materias_count > 0 && $curso->horarios_base_count == 0;

                $turnoDes = match ($curso->turno) {
                    'maniana' => 'Mañana',
                    'tarde'   => 'Tarde',
                    default   => '—',
                };
                $nombreCompleto = "{$curso->anio}° {$curso->division} ({$turnoDes})";

                return [
                    'id' => $curso->id,
                    'nombre_completo' => $nombreCompleto,
                    'anio' => $curso->anio,
                    'division' => $curso->division,
                    'materias_count' => (int) $curso->curso_materias_count,
                    'horarios_count' => (int) $curso->horarios_base_count,
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
