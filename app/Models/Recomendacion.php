<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    use HasFactory;

    protected $table = 'recomendaciones';

    protected $fillable = [
        'user_id',
        'nombre_requerimiento',
        'descripcion',
        'archivo',
        'area_destino',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['archivo_url'];

    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? "api/archivo/recomendacion/{$this->archivo}" : null;
    }

}
