<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HorarioDisponible extends Model
{
    use HasFactory;
    
    protected $table = 'horarios_disponibles';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'disponible',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'disponible' => 'boolean',
    ];

    // Relación: un horario puede tener una reserva
    public function reservas()
    {
        return $this->hasMany(ReservaCita::class, 'horario_id');
    }

    // Saber si este horario ya fue reservado
    public function estaReservado()
    {
        return $this->reservas()->exists();
    }

    // Accesorio: mostrar formato amigable
    public function getHorarioCompletoAttribute()
    {
        return $this->fecha->format('d/m/Y') . ' ' . $this->hora_inicio . ' - ' . $this->hora_fin;
    }

}
