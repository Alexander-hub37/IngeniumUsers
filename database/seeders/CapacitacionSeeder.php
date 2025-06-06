<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Capacitacion;


class CapacitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Capacitacion::create([
            'docente_id' => 1,
            'curso' => 'Educación Inclusiva',
            'institucion' => 'MINEDU',
            'fecha_inicio' => '2020-06-01',
            'fecha_termino' => '2020-08-31',
        ]);

        Capacitacion::create([
            'docente_id' => 2,
            'curso' => 'Uso de TIC en el Aula',
            'institucion' => 'OEI',
            'fecha_inicio' => '2021-03-01',
            'fecha_termino' => '2021-06-30',
        ]);

        Capacitacion::create([
            'docente_id' => 3,
            'curso' => 'Gestión Educativa',
            'institucion' => 'SUNEDU',
            'fecha_inicio' => '2022-01-01',
            'fecha_termino' => '2022-04-30',
        ]);
    }
}
