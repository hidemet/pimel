<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    protected static ?string $password;

    public function definition(): array {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'user',
        ];
    }

    public function unverified(): static {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }

    // Utente admin di esempio
    public function manuelaDonati(): static {
        return $this->state([
            'name' => 'Manuela Donati',
            'email' => 'manudona82@gmail.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
    
    // Utente user di esempio
    public function nicholasDumas(): static {
        return $this->state([
            'name' => 'Nicholas Dumas',
            'email' => 'nicholas.dumas.001@gmail.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}
