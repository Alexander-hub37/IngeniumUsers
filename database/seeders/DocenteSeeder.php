<?php

namespace Database\Seeders;
use App\Models\Docente;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Docente::create([
            'nombres' => 'Ana',
            'apellidos' => 'García',
            'dni' => '12345678',
            'fecha_nacimiento' => '1985-03-10',
            'telefono' => '987654321',
            'direccion' => 'Av. Perú 123',
            'foto' => null,
            'firma' => null,
            'cv' => null
        ]);

        Docente::create([
            'nombres' => 'Carlos',
            'apellidos' => 'López',
            'dni' => '87654321',
            'fecha_nacimiento' => '1990-07-21',
            'telefono' => '912345678',
            'direccion' => 'Calle Lima 456',
            'foto' => null,
            'firma' => null,
            'cv' => null
        ]);

        Docente::create([
            'nombres' => 'Lucía',
            'apellidos' => 'Fernández',
            'dni' => '11223344',
            'fecha_nacimiento' => '1988-11-05',
            'telefono' => '998877665',
            'direccion' => 'Jr. Ayacucho 789',
            'foto' => null,
            'firma' => null,
            'cv' => null
        ]);
    }
}
