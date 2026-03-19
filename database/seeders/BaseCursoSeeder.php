<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\CursoMateria;
use App\Models\CmDocente;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;
use App\Models\EspacioFisico;
use Exception;

abstract class BaseCursoSeeder extends Seeder
{
    protected const FECHA_VIGENCIA_INICIAL = '2026-01-01';

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
        $vigenteDesde = $this->vigenteDesde();

        foreach ($this->materias() as $data) {
            if (!array_key_exists('espacio', $data) || !$data['espacio']) {
                throw new Exception("La materia {$data['nombre']} del curso {$curso->nombre_completo} no tiene espacio definido en el seeder.");
            }

            $materia = Materia::where('nombre', $data['nombre'])->firstOrFail();
            $docente = Docente::where('nombre', $data['docente'])->firstOrFail();
            $espacio = EspacioFisico::query()
                ->where('nombre', $data['espacio'])
                ->firstOrFail();

            $cm = CursoMateria::updateOrCreate(
                [
                    'curso_id' => $curso->id,
                    'materia_id' => $materia->id,
                ],
                [
                    'horas_totales' => $data['horas_totales'],
                    'espacio_fisico_id' => $espacio->id,
                ]
            );

            $cmDocenteVigente = CmDocente::updateOrCreate(
                [
                    'curso_materia_id' => $cm->id,
                    'vigente_desde' => $vigenteDesde,
                ],
                [
                    'docente_id' => $docente->id,
                    'vigente_hasta' => null,
                    'es_vigente' => true,
                ]
            );

            CmDocente::where('curso_materia_id', $cm->id)
                ->where('id', '!=', $cmDocenteVigente->id)
                ->where('es_vigente', true)
                ->update([
                    'es_vigente' => false,
                    'vigente_hasta' => $vigenteDesde,
                ]);
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
        $vigenteDesde = $this->vigenteDesde();

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
                        'bloque_id'  => $bloque->id,
                        'vigente_desde' => $vigenteDesde,
                    ],
                    [
                        'curso_materia_id' => $cursoMateria->id,
                        'vigente_hasta' => null,
                        'es_vigente' => true,
                        'cambio_horario_id' => null,
                    ]
                );
            }
        }
    }


    protected function validarCargaHoraria(Curso $curso): void
    {
        $inconsistencias = CursoMateria::where('curso_id', $curso->id)
            ->with('materia')
            ->withCount([
                'horarioBase as horario_base_count' => function ($query) {
                    $query->vigente();
                }
            ])
            ->get()
            ->filter(fn ($cm) => $cm->horario_base_count != $cm->horas_totales);

        if ($inconsistencias->isNotEmpty()) {
            foreach ($inconsistencias as $cm) {
                echo "Error en {$cm->materia->nombre} "
                    . "(declaradas: {$cm->horas_totales}, "
                    . "cargadas: {$cm->horario_base_count})"
                    . PHP_EOL;
            }

            // llamar al accesor del modelo Curso para mostrar el nombre completo del curso en el mensaje de error
            throw new Exception("Carga horaria inconsistente en {$curso->getNombreCompletoAttribute()}");
        }
    }

    protected function vigenteDesde(): string
    {
        return static::FECHA_VIGENCIA_INICIAL;
    }
}
