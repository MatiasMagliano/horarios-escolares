<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EscuelaDemoSeeder extends Seeder
{
    /**
     * Seed de datos operativos para una escuela demo completa.
     */
    public function run(): void
    {
        $this->call([
            DocentesSeeder::class,
            MateriasSeeder::class,
            EspaciosFisicosSeeder::class,
            BloquesHorariosSeeder::class,
            CursosSeeder::class,
            HorariosSeeder::class,
        ]);
    }
}
