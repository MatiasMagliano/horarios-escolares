<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materias = [
            'Lengua y Literatura',
            'Matemática',
            'Cs. Ns. - Biología',
            'Biología',
            'Cs. Ns. - Física',
            'Física',
            'Cs. Ns. - Química',
            'Química',
            'Cs. Ss. - Geografía',
            'Geografía',
            'Cs. Ss. - Historia',
            'Historia',
            'Leng. Ext. - Inglés',
            'Inglés Técnico',
            'Ed. Art. - Art. Visuales',
            'Ed. Art. - Música',
            'Ed. Art. - Teatro',
            'Ed. Art. - Música/Teatro',
            'Ed. Tecnológica',
            'Psicología',
            'Filosofía',
            'Ciud. y Participación',
            'Ciud. y Política',
            'An. Matemático',
            'Estadística',
            'Econ. y Gest. de la Prod. Ind.',
            'Ed. Física',
            'Dib. Técnico',
            'Taller-Lab.',
            'Taller-Lab. G1',
            'Taller-Lab. G2',
            'F.V.T',
            'Elect. Digital I',
            'Elect. Digital II',
            'Elect. Digital III',
            'Elect. Digital IV',
            'Elect. Analógica I',
            'Elect. Analógica II',
            'Electrotecnia I',
            'Electrotecnia II',
            'Inf. Electrónica I',
            'Inf. Electrónica II',
            'Elect. Industrial I',
            'Elect. Industrial II',
            'Telecomunicaciones I',
            'Telecomunicaciones II',
            'Inst. Industriales',
            'Proy. Integrador',
            'F.A.T',
            'Emprendimientos',
            'Marco Jur. de las Act. Ind.',
            'Hig. y Seg. Laboral',
            'Inf. Aplicada I',
            'Inf. Aplicada II',
            'Lógica Matemática',
            'Programación I',
            'Programación II',
            'Programación III',
            'Recursos Humanos',
            'Base de datos I',
            'Base de datos II',
            'Sist. de Información',
            'Sist. y Telecom.',
            'Sist. de Información',
            'Lab. de Informática',
            'Aplic. de Nuevas Tec.',
        ];

        foreach ($materias as $nombre) {
            Materia::updateOrCreate(
                ['nombre' => $nombre],
                []
            );
        }
    }
}
