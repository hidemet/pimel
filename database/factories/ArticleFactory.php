<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    private const DEFAULT_DESCRIPTION_WORDS = 30;

    public function definition(): array
    {
        $title = $this->generateTitle();
        $body = $this->generateBody();

        return [
            'user_id' => User::factory(),
            'rubric_id' => Rubric::factory(),
            'title' => $title,
            'description' => $this->generateDescription($body),
            'body' => $body,
            'image_path' => $this->generateImagePath(),
            'reading_time' => $this->generateReadingTime(),
            'published_at' => $this->generatePublishedAt(),
        ];
    }

    private function generateTitle(): string
    {
        return fake()->unique()->sentence(fake()->numberBetween(4, 12));
    }

    private function generateBody(): string
    {
        $paragraphs = fake()->paragraphs(fake()->numberBetween(6, 20));

        return '<p>'.implode("</p>\n\n<p>", $paragraphs).'</p>';
    }

    private function generateDescription(string $body): string
    {
        return Str::words(
            strip_tags($body),
            fake()->numberBetween(25, self::DEFAULT_DESCRIPTION_WORDS + 5),
            '...'
        );
    }

    private function generateReadingTime(): int
    {
        return fake()->numberBetween(8, 18);
    }

    private function generateImagePath(): string
    {
        return 'placeholders/article_placeholder_3.jpg';
    }


     // Genera una data di pubblicazione casuale nel passato.
    private function generatePublishedAt()
    {
        return fake()->dateTimeBetween('-180 days', 'now');
    }

    // METODI DI CONVENIENZA

    public function byAuthor($userId): static
    {
        return $this->state(['user_id' => $userId]);
    }

    public function inRubric($rubricId): static
    {
        return $this->state(['rubric_id' => $rubricId]);
    }

    public function withTitle(string $title): static
    {
        return $this->state(['title' => $title]);
    }

    public function withImage(string $imagePath): static
    {
        return $this->state(['image_path' => $imagePath]);
    }

    public function recent(): static
    {
        return $this->state([
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
