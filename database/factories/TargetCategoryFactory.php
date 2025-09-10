<?php

namespace Database\Factories;

use App\Models\TargetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class TargetCategoryFactory extends Factory
{
    public function definition(): array
    {
        return TargetCategory::inRandomOrder()->first()?->toArray() ?? [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'icon_class' => 'settings',
            'description' => 'Categoria di test',
        ];
    }
}
