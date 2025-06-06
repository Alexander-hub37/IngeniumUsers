<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatoProfesional;

class DatoProfesionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DatoProfesional::create([
            'docente_id' => 1,
            'titulo' => 'Licenciatura en Educación',
            'institucion' => 'UNMSM',
            'fecha_inicio' => '2003-01-01',
            'fecha_termino' => '2007-12-31',
        ]);

        DatoProfesional::create([
            'docente_id' => 2,
            'titulo' => 'Maestría en Tecnología Educativa',
            'institucion' => 'PUCP',
            'fecha_inicio' => '2008-03-01',
            'fecha_termino' => '2010-12-31',
        ]);

        DatoProfesional::create([
            'docente_id' => 3,
            'titulo' => 'Ingeniería de Sistemas',
            'institucion' => 'UNI',
            'fecha_inicio' => '2011-01-01',
            'fecha_termino' => '2015-12-31',
        ]);
    }
}
