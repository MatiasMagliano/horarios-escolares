<?php

namespace App\Support\Horarios;

use App\Models\BloqueHorario;
use App\Models\Curso;
use App\Models\CursoMateria;
use App\Models\HorarioBase;
use App\Support\Instituciones\InstitucionContext;
use Illuminate\Support\Collection;

class HorarioCursoGridBuilder
{
    public function __construct(
        private readonly InstitucionContext $institucionContext,
        private readonly BloqueHorarioTemplateManager $bloqueHorarioTemplateManager
    ) {
    }

    public function build(?int $cursoId): Collection
    {
        if (!$cursoId) {
            return collect();
        }

        $curso = $this->getCursoSeleccionado($cursoId);
        if (!$curso) {
            return collect();
        }

        $turnos = $this->resolveTurnosVisibles($curso);
        if ($turnos === []) {
            return collect();
        }

        $bloques = $this->getBloquesForTurnos($turnos);
        $horarios = $this->getHorariosForCursoAndTurnos($cursoId, $turnos);

        return collect($turnos)
            ->mapWithKeys(function (string $turno) use ($bloques, $horarios) {
                $bloquesDelTurno = $bloques->get($turno, collect());

                $grilla = $bloquesDelTurno->mapWithKeys(function ($bloque) use ($horarios, $turno) {
                    return [
                        $bloque->orden => collect([
                            'bloque' => $bloque,
                            'dias' => $horarios->get($turno)?->get($bloque->orden) ?? collect(),
                        ]),
                    ];
                });

                return [$turno => $grilla];
            });
    }

    public function warnings(?int $cursoId): array
    {
        if (!$cursoId) {
            return [];
        }

        $materias = $this->getCursoMateriasConCarga($cursoId);
        if ($materias->isEmpty()) {
            return ['El curso no tiene materias asignadas.'];
        }

        $advertencias = [];

        foreach ($materias as $cursoMateria) {
            if ($cursoMateria->horario_base_count < $cursoMateria->horas_totales) {
                $faltantes = $cursoMateria->horas_totales - $cursoMateria->horario_base_count;
                $advertencias[] = "Faltan asignar {$faltantes} horas de {$cursoMateria->materia->nombre}.";
            }

            if ($cursoMateria->horario_base_count > $cursoMateria->horas_totales) {
                $excedente = $cursoMateria->horario_base_count - $cursoMateria->horas_totales;
                $advertencias[] = "La materia {$cursoMateria->materia->nombre} tiene {$excedente} horas de más.";
            }

            if ($cursoMateria->horario_base_count == 0) {
                $advertencias[] = "La materia {$cursoMateria->materia->nombre} no tiene ninguna hora asignada.";
            }
        }

        return $advertencias;
    }

    public function getCursoSeleccionado(?int $cursoId): ?Curso
    {
        if (!$cursoId) {
            return null;
        }

        return Curso::query()
            ->select(['id', 'turno'])
            ->find($cursoId);
    }

    public function getCursoMateriasConCarga(?int $cursoId): Collection
    {
        if (!$cursoId) {
            return collect();
        }

        return CursoMateria::query()
            ->where('curso_id', $cursoId)
            ->withCount([
                'horarioBase as horario_base_count' => function ($query) {
                    $query->vigente();
                },
            ])
            ->with(['materia', 'cmDocenteVigente.docente'])
            ->get();
    }

    private function getBloquesForTurnos(array $turnos): Collection
    {
        $bloques = BloqueHorario::query()
            ->whereIn('turno', $turnos)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');

        if ($bloques->isNotEmpty()) {
            return $bloques;
        }

        $institucion = $this->institucionContext->institucion();
        if (!$institucion) {
            return $bloques;
        }

        $this->bloqueHorarioTemplateManager->ensureForInstitucion($institucion);

        return BloqueHorario::query()
            ->whereIn('turno', $turnos)
            ->orderBy('orden')
            ->get()
            ->groupBy('turno');
    }

    private function getHorariosForCursoAndTurnos(int $cursoId, array $turnos): Collection
    {
        return HorarioBase::query()
            ->conDocenteVigente()
            ->where('curso_id', $cursoId)
            ->vigente()
            ->whereHas('bloque', fn ($query) => $query->whereIn('turno', $turnos))
            ->get()
            ->groupBy(fn ($horario) => $horario->bloque->turno)
            ->map(function ($items) {
                return $items
                    ->groupBy(fn ($horario) => $horario->bloque->orden)
                    ->map(fn ($bloques) => $bloques->keyBy('dia_semana'));
            });
    }

    private function resolveTurnosVisibles(Curso $curso): array
    {
        $institucion = $this->institucionContext->institucion();

        if (!$institucion) {
            return [$curso->turno, TurnoHelper::contraturnoDe($curso->turno)];
        }

        $turnos = $institucion->turnosVisiblesParaCurso($curso->turno);

        return $turnos !== [] ? $turnos : [$curso->turno];
    }
}
