<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalEmpresa extends Model
{
    use HasFactory;

    protected $table = 'personal_empresa';

    protected $fillable = [
        'user_id',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'nombres',
        'dni',
        'fecha_nacimiento',
        'direccion',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
