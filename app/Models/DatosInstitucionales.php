<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosInstitucionales extends Model
{
    protected $table = 'datos_institucionales';

    protected $fillable = [
        'nombre_institucion',
        'direccion',
        'telefono',
        'email',
        'genero_director',
        'nombre_director',
        'telefono_director',
        'email_director',
        'genero_vicedirector',
        'nombre_vicedirector',
        'telefono_vicedirector',
        'email_vicedirector',
        'vigente'
    ];

    // casteos
    protected $casts = [
        'vigente' => 'boolean',
    ];
}
