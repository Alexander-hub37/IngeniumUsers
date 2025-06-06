<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExperienciaLaboral;


class ExperienciaLaboralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExperienciaLaboral::create([
            'docente_id' => 1,
            'empresa' => 'Colegio Nacional Perú',
            'cargo' => 'Docente de Matemáticas',
            'fecha_inicio' => '2008-01-01',
            'fecha_termino' => '2015-12-31',
        ]);

        ExperienciaLaboral::create([
            'docente_id' => 2,
            'empresa' => 'Universidad Católica',
            'cargo' => 'Investigadora',
            'fecha_inicio' => '2011-01-01',
            'fecha_termino' => '2018-12-31',
        ]);

        ExperienciaLaboral::create([
            'docente_id' => 3,
            'empresa' => 'Ministerio de Educación',
            'cargo' => 'Asesor de Tecnología',
            'fecha_inicio' => '2016-01-01',
            'fecha_termino' => '2020-12-31',
        ]);
    }
}
