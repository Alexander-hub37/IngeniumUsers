<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HorarioDisponible;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GenerarHorarios extends Command
{
    protected $signature = 'horarios:generar';
    protected $description = 'Genera horarios disponibles de lunes a viernes para la próxima semana';

    public function handle()
    {
        $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeek();
        $finSemana = $inicioSemana->copy()->endOfWeek(Carbon::FRIDAY);

        $this->info("Generando horarios del {$inicioSemana->toDateString()} al {$finSemana->toDateString()}");

        $periodo = CarbonPeriod::create($inicioSemana, $finSemana);

        foreach ($periodo as $dia) {
            if ($dia->isWeekday()) {
                // Bloques mañana: 9–13
                $this->crearBloques($dia, 9, 13);

                // Bloques tarde: 14–19
                $this->crearBloques($dia, 14, 19);
            }
        }

        $this->info('Horarios generados correctamente.');
    }

    protected function crearBloques(Carbon $fecha, int $desde, int $hasta)
    {
        for ($hora = $desde; $hora < $hasta; $hora++) {
            $inicio = $fecha->copy()->setTime($hora, 0);
            $fin = $inicio->copy()->addHour();

            // Verificamos si ya existe ese horario para evitar duplicados
            $existe = HorarioDisponible::whereDate('fecha', $inicio->toDateString())
                ->whereTime('hora_inicio', $inicio->format('H:i:s'))
                ->exists();

            if (!$existe) {
                HorarioDisponible::create([
                    'fecha' => $inicio->toDateString(),
                    'hora_inicio' => $inicio->format('H:i:s'),
                    'hora_fin' => $fin->format('H:i:s'),
                    'disponible' => true,
                ]);

                $this->line("✔ {$inicio->format('Y-m-d H:i')} - {$fin->format('H:i')}");
            }
        }
    }
}
