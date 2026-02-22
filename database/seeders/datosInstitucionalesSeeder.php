<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatosInstitucionales;

class datosInstitucionalesSeeder extends Seeder
{
    public function run(): void
    {
        DatosInstitucionales::create([
            'nombre_institucion' => 'IPET Nº 363',
            'direccion' => 'Caraffa 74 de la ciudad de Monte Cristo, Córdoba',
            'telefono' => '123456789',
            'email' => 'ipet363mtecto@gmail.com',
            'genero_director' => 'femenino',
            'nombre_director' => 'Sánchez, Delia Mabel',
            'telefono_director' => '987654321',
            'email_director' => 'director@ejemplo.com',
            'genero_vicedirector' => 'femenino',
            'nombre_vicedirector' => 'Farías, Claudia Patricia',
            'telefono_vicedirector' => '987654322',
            'email_vicedirector' => 'vicedirector@ejemplo.com',
            'vigente' => true
        ]);
    }
}
