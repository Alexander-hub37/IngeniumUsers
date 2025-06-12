<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaCita extends Model
{
    use HasFactory;

    protected $table = 'reservas_citas';

    protected $fillable = [
        'user_id',
        'horario_id',
        'estado',
        'motivo',
    ];

    // Relación: una reserva pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: una reserva pertenece a un horario
    public function horario()
    {
        return $this->belongsTo(HorarioDisponible::class, 'horario_id');
    }

    // Accesorio: nombre del usuario (opcional para mostrar)
    public function getNombreUsuarioAttribute()
    {
        return $this->user->name ?? 'Usuario eliminado';
    }
}
