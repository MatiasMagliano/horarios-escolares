<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\CursoMateria;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;
use Exception;

abstract class BaseCursoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $curso = $this->getCurso();

            $this->seedMaterias($curso);

            $cursoMaterias = $this->getCursoMateriasMap($curso);

            foreach ($this->grillas() as $turnoBloque => $grilla) {
                $this->seedHorario($curso, $turnoBloque, $grilla, $cursoMaterias);
            }

            $this->validarCargaHoraria($curso);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS QUE CADA CURSO DEBE DEFINIR
    |--------------------------------------------------------------------------
    */

    abstract protected function cursoData(): array;
    abstract protected function materias(): array;
    abstract protected function grillas(): array;

    /*
    |--------------------------------------------------------------------------
    | IMPLEMENTACIÓN BASE
    |--------------------------------------------------------------------------
    */

    protected function getCurso(): Curso
    {
        return Curso::where($this->cursoData())->firstOrFail();
    }

    protected function seedMaterias(Curso $curso): void
    {
        foreach ($this->materias() as $data) {

            $materia = Materia::where('nombre', $data['nombre'])->firstOrFail();
            $docente = Docente::where('nombre', $data['docente'])->firstOrFail();

            CursoMateria::updateOrCreate(
                [
                    'curso_id' => $curso->id,
                    'materia_id' => $materia->id,
                ],
                [
                    'horas_totales' => $data['horas_totales'],
                    'docente_id' => $docente->id,
                ]
            );
        }
    }

    protected function getCursoMateriasMap(Curso $curso)
    {
        return CursoMateria::with('materia')
            ->where('curso_id', $curso->id)
            ->get()
            ->keyBy(fn ($cm) => $cm->materia->nombre);
    }

    protected function seedHorario(Curso $curso, string $turnoBloque, array $grilla, $cursoMaterias): void
    {
        $bloques = BloqueHorario::where('turno', $turnoBloque)
            ->get()
            ->keyBy('orden');

        foreach ($grilla as $orden => $dias) {

            $bloque = $bloques->get($orden);

            if (!$bloque) {
                throw new Exception("Bloque {$orden} inexistente para turno {$turnoBloque}");
            }

            foreach ($dias as $dia => [$materiaNombre]) {

                if ($materiaNombre === null) {
                    continue;
                }

                $cursoMateria = $cursoMaterias->get($materiaNombre);

                if (!$cursoMateria) {
                    throw new Exception("Materia {$materiaNombre} no asignada al curso");
                }

                HorarioBase::updateOrCreate(
                    [
                        'curso_id'   => $curso->id,
                        'dia_semana' => $dia,
                        'bloque_id'  => $bloque->id
                    ],
                    [
                        'curso_materia_id' => $cursoMateria->id,
                    ]
                );
            }
        }
    }


    protected function validarCargaHoraria(Curso $curso): void
    {
        $inconsistencias = CursoMateria::where('curso_id', $curso->id)
            ->with('materia')
            ->withCount('horarioBase')
            ->get()
            ->filter(fn ($cm) => $cm->horario_base_count != $cm->horas_totales);

        if ($inconsistencias->isNotEmpty()) {
            foreach ($inconsistencias as $cm) {
                echo "Error en {$cm->materia->nombre} "
                    . "(declaradas: {$cm->horas_totales}, "
                    . "cargadas: {$cm->horario_base_count})"
                    . PHP_EOL;
            }

            throw new Exception("Carga horaria inconsistente en {$curso->nombreCompleto()}");
        }
    }
}
