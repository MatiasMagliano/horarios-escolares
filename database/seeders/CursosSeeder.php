<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            // 1° año - CB
            [1, 'A', 'CB', 'maniana'],
            [1, 'B', 'CB', 'tarde'],
            [1, 'C', 'CB', 'tarde'],
            [1, 'D', 'CB', 'tarde'],

            // 2° año - CB
            [2, 'A', 'CB', 'maniana'],
            [2, 'B', 'CB', 'tarde'],
            [2, 'C', 'CB', 'tarde'],

            // 3° año - CB
            [3, 'A', 'CB', 'maniana'],
            [3, 'B', 'CB', 'tarde'],
            [3, 'C', 'CB', 'tarde'],

            // 4° año - CE
            [4, 'A', 'CE', 'maniana'],
            [4, 'B', 'CE', 'maniana'],

            // 5° año - CE
            [5, 'A', 'CE', 'maniana'],
            [5, 'B', 'CE', 'maniana'],

            // 6° año - CE
            [6, 'A', 'CE', 'maniana'],
            [6, 'B', 'CE', 'tarde'],

            // 7° año - CE
            [7, 'A', 'CE', 'maniana'],
            [7, 'B', 'CE', 'tarde'],
        ];

        foreach ($cursos as [$anio, $division, $ciclo, $turno]) {
            Curso::updateOrCreate(
                [
                    'anio' => $anio,
                    'division' => $division,
                ],
                [
                    'ciclo' => $ciclo,
                    'turno' => $turno,
                ]
            );
        }
    }
}
