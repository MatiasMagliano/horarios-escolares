<?php

namespace App\Models\Concerns;

use App\Models\Institucion;
use App\Support\Instituciones\InstitucionContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToInstitucion
{
    public static function bootBelongsToInstitucion(): void
    {
        static::creating(function ($model) {
            if (!empty($model->institucion_id)) {
                return;
            }

            $institucionId = app(InstitucionContext::class)->id();

            if ($institucionId) {
                $model->institucion_id = $institucionId;
            }
        });

        static::addGlobalScope('institucion', function (Builder $builder) {
            $institucionId = app(InstitucionContext::class)->id();

            if (!$institucionId) {
                return;
            }

            $builder->where($builder->qualifyColumn('institucion_id'), $institucionId);
        });
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function scopeForInstitucion(Builder $query, int $institucionId): Builder
    {
        return $query->withoutGlobalScope('institucion')
            ->where($query->qualifyColumn('institucion_id'), $institucionId);
    }
}
