<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto',
        'cv',
        'telefono',
        'direccion',
        'fecha_nacimiento',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? url("/api/perfil/archivo/foto/{$this->foto}")
            : null;

    }

    public function getCvUrlAttribute()
    {
        return $this->cv
            ? url("/api/perfil/archivo/cv/{$this->cv}")
            : null;
    }

}
