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

            /*
            |--------------------------------------------------------------------------
            | TURNO MAÑANA
            |--------------------------------------------------------------------------
            */
            ['nombre' => 'M1', 'turno' => 'maniana', 'orden' => 1, 'hora_inicio' => '07:10', 'hora_fin' => '07:50'],
            ['nombre' => 'M2', 'turno' => 'maniana', 'orden' => 2, 'hora_inicio' => '07:50', 'hora_fin' => '08:30'],
            ['nombre' => 'M3', 'turno' => 'maniana', 'orden' => 3, 'hora_inicio' => '08:30', 'hora_fin' => '09:10'],
            ['nombre' => 'M4', 'turno' => 'maniana', 'orden' => 4, 'hora_inicio' => '09:25', 'hora_fin' => '10:05'],
            ['nombre' => 'M5', 'turno' => 'maniana', 'orden' => 5, 'hora_inicio' => '10:05', 'hora_fin' => '10:45'],
            ['nombre' => 'M6', 'turno' => 'maniana', 'orden' => 6, 'hora_inicio' => '10:55', 'hora_fin' => '11:35'],
            ['nombre' => 'M7', 'turno' => 'maniana', 'orden' => 7, 'hora_inicio' => '11:35', 'hora_fin' => '12:15'],
            ['nombre' => 'M8', 'turno' => 'maniana', 'orden' => 8, 'hora_inicio' => '12:15', 'hora_fin' => '12:55'],

            /*
            |--------------------------------------------------------------------------
            | CONTRATURNO DE TURNO MAÑANA (VA A LA TARDE)
            |--------------------------------------------------------------------------
            */
            ['nombre' => 'M1', 'turno' => 'contraturno_maniana', 'orden' => 1, 'hora_inicio' => '13:30', 'hora_fin' => '14:10'],
            ['nombre' => 'M2', 'turno' => 'contraturno_maniana', 'orden' => 2, 'hora_inicio' => '14:10', 'hora_fin' => '14:50'],
            ['nombre' => 'M3', 'turno' => 'contraturno_maniana', 'orden' => 3, 'hora_inicio' => '14:50', 'hora_fin' => '15:30'],
            ['nombre' => 'M4', 'turno' => 'contraturno_maniana', 'orden' => 4, 'hora_inicio' => '15:40', 'hora_fin' => '16:20'],
            ['nombre' => 'M5', 'turno' => 'contraturno_maniana', 'orden' => 5, 'hora_inicio' => '16:20', 'hora_fin' => '17:00'],
            ['nombre' => 'M6', 'turno' => 'contraturno_maniana', 'orden' => 6, 'hora_inicio' => '17:15', 'hora_fin' => '17:55'],
            ['nombre' => 'M7', 'turno' => 'contraturno_maniana', 'orden' => 7, 'hora_inicio' => '17:55', 'hora_fin' => '18:35'],
            ['nombre' => 'M8', 'turno' => 'contraturno_maniana', 'orden' => 8, 'hora_inicio' => '18:35', 'hora_fin' => '19:15'],

            /*
            |--------------------------------------------------------------------------
            | TURNO TARDE
            |--------------------------------------------------------------------------
            */
            ['nombre' => 'M1', 'turno' => 'tarde', 'orden' => 1, 'hora_inicio' => '13:30', 'hora_fin' => '14:10'],
            ['nombre' => 'M2', 'turno' => 'tarde', 'orden' => 2, 'hora_inicio' => '14:10', 'hora_fin' => '14:50'],
            ['nombre' => 'M3', 'turno' => 'tarde', 'orden' => 3, 'hora_inicio' => '14:50', 'hora_fin' => '15:30'],
            ['nombre' => 'M4', 'turno' => 'tarde', 'orden' => 4, 'hora_inicio' => '15:40', 'hora_fin' => '16:20'],
            ['nombre' => 'M5', 'turno' => 'tarde', 'orden' => 5, 'hora_inicio' => '16:20', 'hora_fin' => '17:00'],
            ['nombre' => 'M6', 'turno' => 'tarde', 'orden' => 6, 'hora_inicio' => '17:15', 'hora_fin' => '17:55'],
            ['nombre' => 'M7', 'turno' => 'tarde', 'orden' => 7, 'hora_inicio' => '17:55', 'hora_fin' => '18:35'],
            ['nombre' => 'M8', 'turno' => 'tarde', 'orden' => 8, 'hora_inicio' => '18:35', 'hora_fin' => '19:15'],

            /*
            |--------------------------------------------------------------------------
            | CONTRATURNO DE TURNO TARDE (VA A LA MAÑANA)
            |--------------------------------------------------------------------------
            */
            ['nombre' => 'M1', 'turno' => 'contraturno_tarde', 'orden' => 1, 'hora_inicio' => '07:10', 'hora_fin' => '07:50'],
            ['nombre' => 'M2', 'turno' => 'contraturno_tarde', 'orden' => 2, 'hora_inicio' => '07:50', 'hora_fin' => '08:30'],
            ['nombre' => 'M3', 'turno' => 'contraturno_tarde', 'orden' => 3, 'hora_inicio' => '08:30', 'hora_fin' => '09:10'],
            ['nombre' => 'M4', 'turno' => 'contraturno_tarde', 'orden' => 4, 'hora_inicio' => '09:25', 'hora_fin' => '10:05'],
            ['nombre' => 'M5', 'turno' => 'contraturno_tarde', 'orden' => 5, 'hora_inicio' => '10:05', 'hora_fin' => '10:45'],
            ['nombre' => 'M6', 'turno' => 'contraturno_tarde', 'orden' => 6, 'hora_inicio' => '10:55', 'hora_fin' => '11:35'],
            ['nombre' => 'M7', 'turno' => 'contraturno_tarde', 'orden' => 7, 'hora_inicio' => '11:35', 'hora_fin' => '12:15'],
            ['nombre' => 'M8', 'turno' => 'contraturno_tarde', 'orden' => 8, 'hora_inicio' => '12:15', 'hora_fin' => '12:55'],
        ];

        foreach ($bloques as $bloque) {
            BloqueHorario::updateOrCreate(
                [
                    'turno' => $bloque['turno'],
                    'orden' => $bloque['orden'],
                ],
                [
                    'nombre' => $bloque['nombre'],
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $bloque['hora_fin'],
                    'duracion_minutos' => 40,
                    'es_especial' => false,
                ]
            );
        }
    }
}
