<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Docente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombres',
        'apellidos',
        'dni',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'firma',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datosProfesionales()
    {
        return $this->hasMany(DatoProfesional::class);
    }

    public function experienciasLaborales()
    {
        return $this->hasMany(ExperienciaLaboral::class);
    }

    public function capacitaciones()
    {
        return $this->hasMany(Capacitacion::class);
    }

    public function datosIngenium()
    {
        return $this->hasMany(DatoIngenium::class);
    }
}
