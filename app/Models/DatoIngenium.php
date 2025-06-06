<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoIngenium extends Model
{
    use HasFactory;

    protected $table = 'datos_ingenium';

    protected $fillable = [
        'docente_id',
        'curso',
        'calificacion',
        'fecha_inicio',
        'fecha_termino',
        'estado',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

}
