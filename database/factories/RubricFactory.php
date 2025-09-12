<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RubricFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->generateRubricName();

        return [
            'name' => $name,
        ];
    }

    private function generateRubricName(): string
    {
        $words = fake()->unique()->words(rand(2, 4), true);

        return Str::title($words);
    }
}
