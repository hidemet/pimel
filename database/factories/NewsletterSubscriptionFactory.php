<?php

namespace Database\Factories;

use App\Models\NewsletterSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
// Per il token

class NewsletterSubscriptionFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NewsletterSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $isConfirmed = fake()->boolean( 80 );
        // 80% di probabilità di essere confermato

        return [
            'email'        => fake()->unique()->safeEmail(),
            'token'        => $isConfirmed ? null : Str::random( 60 ),
            // Token solo se non confermato
            'confirmed_at' => $isConfirmed ? fake()->dateTimeThisYear() : null,
            // Timestamps created_at e updated_at sono gestiti automaticamente
        ];
    }

    /**
     * Indicate that the subscription is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state( fn( array $attributes ) => [
            'token'        => null,
            'confirmed_at' => now(),
        ] );
    }

    /**
     * Indicate that the subscription is unconfirmed (pending).
     */
    public function unconfirmed(): static
    {
        return $this->state( fn( array $attributes ) => [
            'token'        => Str::random( 60 ),
            'confirmed_at' => null,
        ] );
    }
}