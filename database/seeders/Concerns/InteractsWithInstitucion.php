<?php

namespace Database\Seeders\Concerns;

use App\Models\Institucion;
use Illuminate\Database\Seeder;

trait InteractsWithInstitucion
{
    protected ?Institucion $institucionCache = null;

    protected function institucion(): Institucion
    {
        if ($this->institucionCache) {
            return $this->institucionCache;
        }

        $slug = env('SEED_DEFAULT_INSTITUCION_SLUG', 'escuela-demo');

        return $this->institucionCache = Institucion::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    protected function institucionId(): int
    {
        return (int) $this->institucion()->id;
    }
}
