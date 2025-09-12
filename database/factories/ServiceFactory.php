<?php

namespace Database\Factories;

use App\Models\TargetCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->generateServiceName(),
            'description' => fake()->paragraph(rand(3, 5)),
            'target_audience' => fake()->sentence(rand(8, 15)),
            'objectives' => $this->generateListItems(rand(3, 5)),
            'modalities' => $this->generateListItems(rand(2, 4)),
            'target_category_id' => $this->getRandomTargetCategory(),
        ];
    }

    private function generateServiceName(): string
    {
        $words = fake()->unique()->words(rand(3, 6), true);

        return Str::title($words);
    }

    private function generateListItems(int $count): string
    {
        $items = [];
        for ($i = 0; $i < $count; ++$i) {
            $items[] = '- '.fake()->sentence(rand(5, 10));
        }

        return implode("\n", $items);
    }

    private function getRandomTargetCategory(): ?int
    {
        if (0 === TargetCategory::count()) {
            return null;
        }

        $shouldAssignCategory = fake()->boolean(80);

        if (! $shouldAssignCategory) {
            return null;
        }

        return TargetCategory::inRandomOrder()->first()->id;
    }
}
