<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatoIngenium;


class DatoIngeniumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DatoIngenium::create([
            'docente_id' => 1,
            'curso' => 'Innovación Educativa',
            'calificacion' => '3',
            'fecha_inicio' => '2023-01-10',
            'fecha_termino' => '2023-03-15',
            'estado' => 'Finalizado',
        ]);

        DatoIngenium::create([
            'docente_id' => 2,
            'curso' => 'Metodologías Activas',
            'calificacion' => '4',
            'fecha_inicio' => '2023-04-05',
            'fecha_termino' => '2023-06-10',
            'estado' => 'Finalizado',
        ]);

        DatoIngenium::create([
            'docente_id' => 3,
            'curso' => 'Uso de TICs en el Aula',
            'calificacion' => '5',
            'fecha_inicio' => '2023-07-01',
            'fecha_termino' => '2023-09-01',
            'estado' => 'Finalizado',
        ]);
    }
}
