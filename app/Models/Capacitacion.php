<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacion extends Model
{
    use HasFactory;

    protected $table = 'capacitaciones';

    protected $fillable = [
        'docente_id',
        'curso',
        'institucion',
        'fecha_inicio',
        'fecha_termino',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
