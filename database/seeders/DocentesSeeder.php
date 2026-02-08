<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Docente;


class DocentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $docentes = [
            'Marcos Morales',
            'M. Elena Mansilla',
            'Ariel Ardiles',
            'Sofía Rodriguez',
            'Claudia Ramadán',
            'Noelia Martinez Villegas',
            'Soledad González',
            'Yanina Funes',
            'Ivana Ribodino',
            'Nadia Llarrull',
            'Luciana Sosa Grión',
            'Verónica Gizzi',
            'Vanesa Farías',
            'Marianela Pecorari',
            'Carina Chialva',
            'Franco Morano',
            'Martín Andrada',
            'Brenda Quiroga',
            'Yolanda Sucheyre',
            'Marisa Morales',
            'Erick Zaccagnini',
            'Belén Ramos',
            'Natacha Marangón',
            'Yanina Lerda',
            'Marianela Gatti',
            'Sonia Bruno',
            'Facundo Zurita',
            'Andrea Chiappori',
            'Pablo Bulacio',
            'Vanina Ibarra',
            'Adam Luna',
            'Pablo Barac',
            'Sandra Occhipinti',
            'Esteban Menti',
            'Javier Berra',
            'Ian Concepción',
            'Lorena Vera',
            'Matías Magliano',
            'Piscila Calizaya',
            'Carolina Molina',
            'Greca Colazo',
            'Laura Perez',
            'Martín Franch',
            'Claudia Farías',
            'Laura Díaz',
            'Nancy Scipioni',
            'Yanina Sanchez',
            'César Giacchini',
            'Domingo Greggio',
            'Bruno/Morano',
            'Priscila Calizaya',
            'Flavia Eberhardt',
            'Nicolás Coria',
            'Miriam Porcel',
            'Patricia Solís',
            'Micaela Acuña',
            'Florentina Arinci',
            'Mónica Rosso',
            'Marianela Gatti',
            'Carolina Rojas'
        ];

        foreach ($docentes as $nombre) {
            Docente::updateOrCreate(
                ['nombre' => $nombre],
                ['telefono' => '351' . fake()->numberBetween(4000000, 4999999)]
            );
        }
    }
}
