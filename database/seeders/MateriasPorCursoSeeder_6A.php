<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;

class MateriasPorCursoSeeder_6A extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 6,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        $materias = [
            ['nombre' => 'Lengua y Lit.', 'horas_totales' => 3], 
            ['nombre' => 'An. Matemático', 'horas_totales' => 5], 
            ['nombre' => 'Filosofía', 'horas_totales' => 3], 
            ['nombre' => 'Ciud. y Política', 'horas_totales' => 3],
            ['nombre' => 'Inglés', 'horas_totales' => 3], 
            ['nombre' => 'Teatro', 'horas_totales' => 2],
            ['nombre' => 'Econ. y Gestión de la Prod. Ind.', 'horas_totales' => 4],
            ['nombre' => 'Elect. Digital III', 'horas_totales' => 6],
            ['nombre' => 'Elect. Industrial I', 'horas_totales' => 6],
            ['nombre' => 'Telecomunicaciones', 'horas_totales' => 6],
            ['nombre' => 'Inst. Industriales', 'horas_totales' => 7],
        ];

        foreach ($materias as $materia) {
            Materia::updateOrCreate(
                [
                    'nombre' => $materia['nombre'],
                    'curso_id' => $curso->id,
                ],
                [
                    'horas_totales' => $materia['horas_totales'],
                ]
            );
        }
    }
}
