<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'institucion_activa_id',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function instituciones(): BelongsToMany
    {
        return $this->belongsToMany(Institucion::class, 'institucion_user')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function institucionActiva(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'institucion_activa_id');
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    public function puedeAccederInstitucion(int $institucionId): bool
    {
        if ($this->isSuperAdmin()) {
            return Institucion::query()
                ->whereKey($institucionId)
                ->where('activo', true)
                ->exists();
        }

        return $this->instituciones()
            ->where('datos_institucionales.id', $institucionId)
            ->wherePivot('activo', true)
            ->where('datos_institucionales.activo', true)
            ->exists();
    }

    public function activarInstitucion(Institucion $institucion): void
    {
        $this->forceFill([
            'institucion_activa_id' => $institucion->id,
        ])->save();

        $this->setRelation('institucionActiva', $institucion);
    }

    public function getInstitucionesDisponiblesAttribute()
    {
        if ($this->isSuperAdmin()) {
            return Institucion::query()
                ->where('activo', true)
                ->orderBy('nombre_institucion')
                ->get();
        }

        return $this->instituciones()
            ->where('datos_institucionales.activo', true)
            ->wherePivot('activo', true)
            ->orderBy('nombre_institucion')
            ->get();
    }
}
