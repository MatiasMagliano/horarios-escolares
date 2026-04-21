<?php

namespace App\Models;

use App\Models\Concerns\BelongsToInstitucion;
use Illuminate\Database\Eloquent\Model;

class EspacioFisico extends Model
{
    use BelongsToInstitucion;

    public const TIPO_AULA = 'aula';
    public const TIPO_LAB_INFORMATICA = 'lab-informatica';
    public const TIPO_LAB_ELECTRONICA = 'lab-electronica';
    public const TIPO_LAB_TALLER = 'lab-taller';
    public const TIPO_PATIO = 'patio';

    protected $table = 'espacios_fisicos';

    protected $fillable = [
        'institucion_id',
        'nombre',
        'tipo',
        'activo',
    ];

    public function cursoMaterias()
    {
        return $this->hasMany(CursoMateria::class);
    }

    public static function tiposDisponibles(): array
    {
        return [
            self::TIPO_AULA,
            self::TIPO_LAB_INFORMATICA,
            self::TIPO_LAB_ELECTRONICA,
            self::TIPO_LAB_TALLER,
            self::TIPO_PATIO,
        ];
    }
}
