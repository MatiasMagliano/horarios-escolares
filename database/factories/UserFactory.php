<?php

namespace Database\Factories;

use App\Models\Institucion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withInstitution(): static
    {
        return $this->afterCreating(function (\App\Models\User $user) {
            $institucion = Institucion::query()->create([
                'nombre_institucion' => fake()->company(),
                'slug' => fake()->unique()->slug(),
                'direccion' => fake()->address(),
                'anio_maximo' => 7,
                'tiene_turno_maniana' => true,
                'tiene_turno_tarde' => true,
                'tiene_contraturno_maniana' => false,
                'tiene_contraturno_tarde' => false,
                'activo' => true,
            ]);

            $user->instituciones()->attach($institucion->id, ['activo' => true]);
            $user->forceFill(['institucion_activa_id' => $institucion->id])->save();
        });
    }
}
