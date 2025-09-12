<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'body' => fake()->paragraph(rand(1, 5)),
            'is_approved' => fake()->boolean(90),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function forArticle($articleId): static
    {
        return $this->state(['article_id' => $articleId]);
    }

    public function byUser($userId): static
    {
        return $this->state(['user_id' => $userId]);
    }

    public function approved(): static
    {
        return $this->state(['is_approved' => true]);
    }

    public function pending(): static
    {
        return $this->state(['is_approved' => false]);
    }

    public function withBody(string $body): static
    {
        return $this->state(['body' => $body]);
    }

    public function recent(): static
    {
        return $this->state(['created_at' => fake()->dateTimeBetween('-1 month', 'now')]);
    }
}
