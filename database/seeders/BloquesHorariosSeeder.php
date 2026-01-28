<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloqueHorario;

class BloquesHorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloques = [
            [
                'nombre' => 'M1',
                'turno' => 'maniana',
                'hora_inicio' => '07:10',
                'hora_fin' => '07:50',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M2',
                'turno' => 'maniana',
                'hora_inicio' => '07:50',
                'hora_fin' => '08:30',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M3',
                'turno' => 'maniana',
                'hora_inicio' => '08:30',
                'hora_fin' => '09:10',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 15' (09:10 - 09:25) → NO es bloque
            [
                'nombre' => 'M4',
                'turno' => 'maniana',
                'hora_inicio' => '09:25',
                'hora_fin' => '10:05',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M5',
                'turno' => 'maniana',
                'hora_inicio' => '10:05',
                'hora_fin' => '10:45',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 10' (10:45 - 10:55) → NO es bloque
            [
                'nombre' => 'M6',
                'turno' => 'maniana',
                'hora_inicio' => '10:55',
                'hora_fin' => '11:35',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M7',
                'turno' => 'maniana',
                'hora_inicio' => '11:35',
                'hora_fin' => '12:15',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
        ];

        foreach ($bloques as $bloque) {
            BloqueHorario::updateOrCreate(
                [
                    'nombre' => $bloque['nombre'],
                    'turno'  => $bloque['turno'],
                ],
                $bloque
            );
        }
    }
}
