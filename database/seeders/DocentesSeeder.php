<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Docente;


class DocentesSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        $docentes = [
            ['nombre' => 'Marcos Morales', 'nombre_completo' => 'Morales, Marcos David', 'dni' => '41523749', 'nacimiento' => '1999-01-16'],
            ['nombre' => 'M. Elena Mansilla', 'nombre_completo' => 'Mansilla, María Elena', 'dni' => '21514450', 'nacimiento' => '1973-11-02'],
            ['nombre' => 'Ariel Ardiles', 'nombre_completo' => 'Ardiles, Ariel Alejandro', 'dni' => '23694817', 'nacimiento' => '1974-01-03'],
            ['nombre' => 'Sofía Rodriguez', 'nombre_completo' => 'Rodriguez, Sofía Lorena', 'dni' => '25457297', 'nacimiento' => '1976-09-30'],
            ['nombre' => 'Claudia Ramadán', 'nombre_completo' => 'Ramadán, Claudia Carina', 'dni' => '26456238', 'nacimiento' => '1978-02-09'],
            ['nombre' => 'Noelia Martinez Villegas', 'nombre_completo' => 'Martinez Villegas, Noelia Vanesa', 'dni' => '30116916', 'nacimiento' => '1983-08-19'],
            ['nombre' => 'Soledad González', 'nombre_completo' => 'González, Julieta Soledad', 'dni' => '25581934', 'nacimiento' => '1977-01-26'],
            ['nombre' => 'Yanina Funes', 'nombre_completo' => 'Funes, Yanina', 'dni' => '26444996', 'nacimiento' => '1978-06-26'],
            ['nombre' => 'Ivana Ribodino', 'nombre_completo' => 'Ribodino, Ivana del Valle', 'dni' => '23577813', 'nacimiento' => '1974-01-06'],
            ['nombre' => 'Nadia Llarrull', 'nombre_completo' => 'Llarrull, Nadia', 'dni' => '33645948', 'nacimiento' => '1988-06-28'],
            ['nombre' => 'Luciana Sosa Grión', 'nombre_completo' => 'Sosa Grión, Luciana Nataly María', 'dni' => '36714823', 'nacimiento' => '1992-02-20'],
            ['nombre' => 'Verónica Gizzi', 'nombre_completo' => 'Gizzi, Selva Verónica', 'dni' => '27247960', 'nacimiento' => '1979-05-03'],
            ['nombre' => 'Vanesa Farías', 'nombre_completo' => 'Farías, Vanesa Soledad', 'dni' => '30701275', 'nacimiento' => '1984-06-03'],
            ['nombre' => 'Marianela Pecorari', 'nombre_completo' => 'Pecorari, Marianela', 'dni' => '30239238', 'nacimiento' => '1983-03-10'],
            ['nombre' => 'Carina Chialva', 'nombre_completo' => 'Chialva, Carina Vanessa', 'dni' => '25063686', 'nacimiento' => '1976-12-30'],
            ['nombre' => 'Franco Morano', 'nombre_completo' => 'Morano, Franco Alexis', 'dni' => '36714992', 'nacimiento' => '1993-12-08'],
            ['nombre' => 'Martín Andrada', 'nombre_completo' => 'Andrada, César Martín', 'dni' => '34306259', 'nacimiento' => '1989-01-21'],
            ['nombre' => 'Brenda Quiroga', 'nombre_completo' => 'Quiroga, Brenda Micaela', 'dni' => '39969376', 'nacimiento' => '1998-11-03'],
            ['nombre' => 'Yolanda Sucheyre', 'nombre_completo' => 'Sucheyre, Yolanda Marcela', 'dni' => '11863050', 'nacimiento' => '1956-03-03'],
            ['nombre' => 'Marisa Morales', 'nombre_completo' => 'Morales, Marisa del Valle', 'dni' => '24071148', 'nacimiento' => '1974-11-09'],
            ['nombre' => 'Erick Zaccagnini', 'nombre_completo' => 'Zaccagnini, Erick', 'dni' => '24778586', 'nacimiento' => '1976-03-18'],
            ['nombre' => 'Belén Ramos', 'nombre_completo' => 'Ramos, María Belén', 'dni' => '33959346', 'nacimiento' => '1989-11-24'],
            ['nombre' => 'Natacha Marangón', 'nombre_completo' => 'Marangón Castillo, Natacha del Valle', 'dni' => '32960259', 'nacimiento' => '1987-11-28'],
            ['nombre' => 'Yanina Lerda', 'nombre_completo' => 'Lerda Tejeda, Araceli Yanina', 'dni' => '37318263', 'nacimiento' => '1993-04-05'],
            ['nombre' => 'Marianela Gatti', 'nombre_completo' => 'Gatti, Marianela Belén', 'dni' => '32960252', 'nacimiento' => '1987-11-14'],
            ['nombre' => 'Sonia Bruno', 'nombre_completo' => 'Bruno, Sonia Mariela', 'dni' => '24280411', 'nacimiento' => '1975-02-20'],
            ['nombre' => 'Facundo Zurita', 'nombre_completo' => 'Zurita, Facundo Martín', 'dni' => '34246724', 'nacimiento' => '1989-01-31'],
            ['nombre' => 'Andrea Chiappori', 'nombre_completo' => 'Chiappori, Andrea Beatriz', 'dni' => '23736427', 'nacimiento' => '1974-04-13'],
            ['nombre' => 'Pablo Bulacio', 'nombre_completo' => 'Bulacio, Pablo', 'dni' => '24759534', 'nacimiento' => '1975-09-19'],
            ['nombre' => 'Vanina Ibarra', 'nombre_completo' => 'Ibarra, Vanina Soledad', 'dni' => '33645907', 'nacimiento' => '1988-03-20'],
            ['nombre' => 'Adam Luna', 'nombre_completo' => 'Luna, Adam Anselmo', 'dni' => '34006802', 'nacimiento' => '1989-04-20'],
            ['nombre' => 'Pablo Barac', 'nombre_completo' => 'Barac, Pablo Martín', 'dni' => '23167952', 'nacimiento' => '1973-08-18'],
            ['nombre' => 'Sandra Occhipinti', 'nombre_completo' => 'Occhipinti, Sandra Irene', 'dni' => '16684042', 'nacimiento' => '1963-12-09'],
            ['nombre' => 'Esteban Menti', 'nombre_completo' => 'Menti, Esteban Sergio', 'dni' => '30967959', 'nacimiento' => '1984-05-11'],
            ['nombre' => 'Javier Berra', 'nombre_completo' => 'Berra, Javier Eduardo', 'dni' => '25858654', 'nacimiento' => '1977-08-13'],
            ['nombre' => 'Ian Concepción', 'nombre_completo' => 'Concepción Alvarado, Ian Erik', 'dni' => '24778586', 'nacimiento' => '1971-10-25'],
            ['nombre' => 'Lorena Vera', 'nombre_completo' => 'Vera, Lorena Silvana', 'dni' => '25401078', 'nacimiento' => '1976-09-29'],
            ['nombre' => 'Matías Magliano', 'nombre_completo' => 'Magliano, Matías José', 'dni' => '29714640', 'nacimiento' => '1982-10-28'],
            ['nombre' => 'Piscila Calizaya', 'nombre_completo' => 'Martinez Calizaya, Ana Piscila', 'dni' => '43134003', 'nacimiento' => '2000-11-07'],
            ['nombre' => 'Carolina Molina', 'nombre_completo' => 'Molina, Ana Carolina', 'dni' => '27296688', 'nacimiento' => '1979-10-24'],
            ['nombre' => 'Greca Colazo', 'nombre_completo' => 'Colazo, Greca Ibis', 'dni' => '27748174', 'nacimiento' => '1978-12-02'],
            ['nombre' => 'Laura Perez', 'nombre_completo' => 'Perez, Laura Inés', 'dni' => '30124411', 'nacimiento' => '1983-04-05'],
            ['nombre' => 'Martín Franch', 'nombre_completo' => 'Franch, Alejandro Martín', 'dni' => '23520781', 'nacimiento' => '1974-04-05'],
            ['nombre' => 'Claudia Farías', 'nombre_completo' => 'Farías, Claudia Patricia', 'dni' => '26244771', 'nacimiento' => '1977-10-13'],
            ['nombre' => 'Laura Díaz', 'nombre_completo' => 'Díaz, Laura Soledad', 'dni' => '29473427', 'nacimiento' => '1982-04-22'],
            ['nombre' => 'Nancy Scipioni', 'nombre_completo' => 'Scipioni, Nancy del Valle', 'dni' => '21390673', 'nacimiento' => '1970-01-11'],
            ['nombre' => 'Yanina Sanchez', 'nombre_completo' => 'Sanchez, Yanina Belén', 'dni' => '43602607', 'nacimiento' => '2000-11-09'],
            ['nombre' => 'César Giacchini', 'nombre_completo' => 'Giachini, César Darío', 'dni' => '25857947', 'nacimiento' => '1977-03-25'],
            ['nombre' => 'Domingo Greggio', 'nombre_completo' => 'Greggio, Rafael Domingo', 'dni' => '13257847', 'nacimiento' => '1957-09-16'],
            ['nombre' => 'Bruno/Morano', 'nombre_completo' => 'no_aplica', 'dni' => 'no_aplica', 'nacimiento' => '2026-01-01'],
            ['nombre' => 'Flavia Eberhardt', 'nombre_completo' => 'Eberhardt, Flavia', 'dni' => '21514456', 'nacimiento' => '1973-11-09'],
            ['nombre' => 'Nicolás Coria', 'nombre_completo' => 'Coria, José Nicolás', 'dni' => '18217384', 'nacimiento' => '1967-08-24'],
            ['nombre' => 'Miriam Porcel', 'nombre_completo' => 'Porcel, Miriam del Valle', 'dni' => '21400238', 'nacimiento' => '1970-01-15'],
            ['nombre' => 'Patricia Solís', 'nombre_completo' => 'Solís, Patricia Alejandra', 'dni' => '29207984', 'nacimiento' => '1981-12-17'],
            ['nombre' => 'Micaela Acuña', 'nombre_completo' => 'Acuña, Micaela', 'dni' => '40777444', 'nacimiento' => '1998-01-01'],
            ['nombre' => 'Florentina Arinci', 'nombre_completo' => 'Arinci Rossi, Florentina', 'dni' => '37285956', 'nacimiento' => '1993-12-29'],
            ['nombre' => 'Mónica Rosso', 'nombre_completo' => 'Rosso, Mónica Marta', 'dni' => '24799608', 'nacimiento' => '1978-01-01'],
            ['nombre' => 'Carolina Rojas', 'nombre_completo' => 'Rojas, Daiana Carolina', 'dni' => '37095367', 'nacimiento' => '1992-11-09'],
            ['nombre' => 'Miguel Sosa', 'nombre_completo' => 'Sosa, Miguel Ángel', 'dni' => '21039313', 'nacimiento' => '1969-10-23'],
        ];

        foreach ($docentes as $docente) {
            Docente::updateOrCreate(
                ['nombre' => $docente['nombre']],
                [
                    'nombre_completo' => $docente['nombre_completo'],
                    'dni' => $docente['dni'],
                    'nacimiento' => $docente['nacimiento'],
                    'telefono' => '351' . fake()->numberBetween(4000000, 4999999)
                ]
            );
        }
    }
}
