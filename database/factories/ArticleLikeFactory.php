<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleLikeFactory extends Factory
{
    public function definition(): array
    {
        return [
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

    public function recent(): static
    {
        return $this->state(['created_at' => fake()->dateTimeBetween('-1 month', 'now')]);
    }

    public function onDate($date): static
    {
        return $this->state(['created_at' => $date]);
    }
}
