<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->optional()->sentence(rand(3, 7)),
            'message' => fake()->paragraph(rand(2, 6)),
            'service_of_interest' => $this->getRandomServiceOfInterest(),
            'is_read' => fake()->boolean(30),
            'archived_at' => fake()->optional(0.1)->dateTimeThisYear(),
        ];
    }

    private function getRandomServiceOfInterest(): ?string
    {
        $shouldAssignService = fake()->boolean(60);

        if (! $shouldAssignService) {
            return null;
        }

        $activeService = Service::inRandomOrder()
            ->first();

        return $activeService?->name;
    }
}
