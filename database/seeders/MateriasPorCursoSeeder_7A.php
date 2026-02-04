<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;

class MateriasPorCursoSeeder_7A extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 selección de curso
        $curso = Curso::where([
            'anio' => 7,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        $materias = [
            ['nombre' => 'F.A.T', 'horas_totales' => 10], 
            ['nombre' => 'Emprendimientos', 'horas_totales' => 4], 
            ['nombre' => 'M. Jurídico de las Act. Indust.', 'horas_totales' => 3], 
            ['nombre' => 'Higiene y Seguridad', 'horas_totales' => 3],
            ['nombre' => 'Inglés técnico', 'horas_totales' => 3], 
            ['nombre' => 'Proy. Integrador', 'horas_totales' => 6],
            ['nombre' => 'Telecomunicaciones II', 'horas_totales' => 5],
            ['nombre' => 'Elect. Digital IV', 'horas_totales' => 5],
            ['nombre' => 'Elect. Industrial II', 'horas_totales' => 5],
        ];

        // 2 itera sobre las materias y las crea
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
