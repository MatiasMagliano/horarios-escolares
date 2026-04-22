<?php

namespace Database\Seeders;

use App\Support\Horarios\BloqueHorarioTemplateManager;
use Database\Seeders\Concerns\InteractsWithInstitucion;
use Illuminate\Database\Seeder;

class BloquesHorariosSeeder extends Seeder
{
    use InteractsWithInstitucion;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(BloqueHorarioTemplateManager::class)->ensureForInstitucion($this->institucion());
    }
}
