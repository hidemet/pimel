<?php

namespace Database\Factories;

use App\Models\TargetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TargetCategoryFactory extends Factory
{
    protected $model = TargetCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(rand(1, 3), true); // Es. "Nuova Categoria Target"
        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'icon_class' => fake()->randomElement(['face', 'pets', 'work', 'home', 'settings']), // Esempi di icone
            'description' => fake()->optional()->sentence(),
        ];
    }
}