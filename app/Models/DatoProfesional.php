<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoProfesional extends Model
{
    use HasFactory;

    protected $table = 'datos_profesionales';
    
    protected $fillable = [
        'docente_id',
        'titulo',
        'institucion',
        'fecha_inicio',
        'fecha_termino',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
