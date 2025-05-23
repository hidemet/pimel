<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make(
                'password' ),
            'remember_token'    => Str::random( 10 ),
            'role'              => 'user',
            // Default role per gli utenti creati dalla factory
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state( fn( array $attributes ) => [
            'email_verified_at' => null,
        ] );
    }

    /**
     * Indicate that the model's role should be admin.
     */
    public function admin(): static
    {
        return $this->state( fn( array $attributes ) => [
            'role' => 'admin',
        ] );
    }

    /**
     * Create a specific admin user (Manuela Donati).
     */
    public function manuelaDonati(): static
    {
        return $this->state( fn( array $attributes ) => [
            'name'              => 'Manuela Donati',
            'email'             => 'jackpagoda.lll@gmail.com',
            // O un'altra email admin che preferisci
            'role'              => 'admin',
            'email_verified_at' => now(),
            'password'          => Hash::make( 'PimelAdmin!2024' ),
            // Scegli una password sicura
        ] );
    }

}
