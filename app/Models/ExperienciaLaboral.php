<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienciaLaboral extends Model
{
    use HasFactory;

    protected $table = 'experiencias_laborales';
    
    protected $fillable = [
        'docente_id',
        'empresa',
        'cargo',
        'fecha_inicio',
        'fecha_termino',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
