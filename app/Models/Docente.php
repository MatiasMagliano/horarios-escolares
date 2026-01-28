<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'nombre',
        'telefono'
    ];

    public function horariosBase()
    {
        return $this->hasMany(HorarioBase::class);
    }
}
