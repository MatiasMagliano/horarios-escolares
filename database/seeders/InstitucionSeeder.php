<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        $nombre = env('SEED_DEFAULT_INSTITUCION_NOMBRE', 'Escuela Demo');
        $slug = env('SEED_DEFAULT_INSTITUCION_SLUG', Str::slug($nombre));

        Institucion::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'nombre_institucion' => $nombre,
                'direccion' => 'A definir',
                'anio_maximo' => (int) env('SEED_DEFAULT_INSTITUCION_ANIO_MAXIMO', 7),
                'tiene_turno_maniana' => true,
                'tiene_turno_tarde' => true,
                'tiene_contraturno_maniana' => false,
                'tiene_contraturno_tarde' => false,
                'activo' => true,
            ]
        );
    }
}
