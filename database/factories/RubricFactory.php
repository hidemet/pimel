<?php

namespace Database\Factories;

use App\Models\Rubric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

// Per generare lo slug se necessario (anche se il modello lo fa)

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rubric>
 */
class RubricFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rubric::class; // Specifica il modello

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $name = fake()->unique()->words( rand( 2, 4 ), true );
        // Genera un nome univoco di 2-4 parole
        $name = Str::title( $name );
        // Capitalizza la prima lettera di ogni parola

        return [
            'name'        => $name,

            // Lo slug verrà generato automaticamente dal mutator nel modello Rubric

            // 'slug' => Str::slug($name), // Non strettamente necessario qui se il modello lo gestisce
            'description' => fake()->optional()->sentence( 10 ),
            // Descrizione opzionale, una frase di 10 parole
        ];
    }
}