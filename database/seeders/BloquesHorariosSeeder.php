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
                'orden' => 1,
                'hora_inicio' => '07:10',
                'hora_fin' => '07:50',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M2',
                'turno' => 'maniana',
                'orden' => 2,
                'hora_inicio' => '07:50',
                'hora_fin' => '08:30',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M3',
                'turno' => 'maniana',
                'orden' => 3,
                'hora_inicio' => '08:30',
                'hora_fin' => '09:10',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 15' (09:10 - 09:25) → NO es bloque
            [
                'nombre' => 'M4',
                'turno' => 'maniana',
                'orden' => 4,
                'hora_inicio' => '09:25',
                'hora_fin' => '10:05',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M5',
                'turno' => 'maniana',
                'orden' => 5,
                'hora_inicio' => '10:05',
                'hora_fin' => '10:45',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 10' (10:45 - 10:55) → NO es bloque
            [
                'nombre' => 'M6',
                'turno' => 'maniana',
                'orden' => 6,
                'hora_inicio' => '10:55',
                'hora_fin' => '11:35',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M7',
                'turno' => 'maniana',
                'orden' => 7,
                'hora_inicio' => '11:35',
                'hora_fin' => '12:15',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M8',
                'turno' => 'maniana',
                'orden' => 8,
                'hora_inicio' => '12:15',
                'hora_fin' => '12:55',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M1',
                'turno' => 'tarde',
                'orden' => 1,
                'hora_inicio' => '13:30',
                'hora_fin' => '14:10',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M2',
                'turno' => 'tarde',
                'orden' => 2,
                'hora_inicio' => '14:10',
                'hora_fin' => '14:50',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M3',
                'turno' => 'tarde',
                'orden' => 3,
                'hora_inicio' => '14:50',
                'hora_fin' => '15:30',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 10' (15:30 - 15:40) → NO es bloque
            [
                'nombre' => 'M4',
                'turno' => 'tarde',
                'orden' => 4,
                'hora_inicio' => '15:40',
                'hora_fin' => '16:20',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M5',
                'turno' => 'tarde',
                'orden' => 5,
                'hora_inicio' => '16:20',
                'hora_fin' => '17:00',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            // Recreo 15' (17:00 - 17:15) → NO es bloque
            [
                'nombre' => 'M6',
                'turno' => 'tarde',
                'orden' => 6,
                'hora_inicio' => '17:15',
                'hora_fin' => '17:55',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M7',
                'turno' => 'tarde',
                'orden' => 7,
                'hora_inicio' => '17:55',
                'hora_fin' => '18:35',
                'duracion_minutos' => 40,
                'es_especial' => false,
            ],
            [
                'nombre' => 'M8',
                'turno' => 'tarde',
                'orden' => 8,
                'hora_inicio' => '18:35',
                'hora_fin' => '19:15',
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
