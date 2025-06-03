<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;



class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellidos',
        'dni',
        'email',
        'telefono',
        'foto',
        'firma',
        'cv',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['foto_url', 'firma_url', 'cv_url'];

    public function getFotoUrlAttribute()
    {
        return $this->foto 
            ? url("/api/docentes/archivo/foto/{$this->foto}") 
            : null;
    }

    public function getFirmaUrlAttribute()
    {
        return $this->firma 
            ? url("/api/docentes/archivo/firma/{$this->firma}") 
            : null;
    }

    public function getCvUrlAttribute()
    {
        return $this->cv 
            ? url("/api/docentes/archivo/cv/{$this->cv}") 
            : null;
    }
}
