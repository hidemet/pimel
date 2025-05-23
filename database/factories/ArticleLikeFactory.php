<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleLikeFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleLike::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $article = Article::inRandomOrder()->where( 'status', 'published' )
            ->first() ?? Article::factory()->published()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'article_id' => $article->id,
            'user_id'    => $user->id,

            // Timestamps (created_at, updated_at) verranno gestiti automaticamente da Eloquent

            // e possono essere personalizzati nel seeder se necessario, ad esempio
            // per farli apparire dopo la pubblicazione dell'articolo.
            'created_at' => fake()->dateTimeBetween( $article->published_at ??
                $article->created_at, 'now' ),
        ];
    }

    /**
     * Assign a specific article to the like.
     */
    public function forArticle( Article $article ): static
    {
        return $this->state( fn( array $attributes ) => [
            'article_id' => $article->id,
            'created_at' => fake()->dateTimeBetween( $article->published_at ??
                $article->created_at, 'now' ), // Aggiorna created_at
        ] );
    }

    /**
     * Assign a specific user to the like.
     */
    public function byUser( User $user ): static
    {
        return $this->state( fn( array $attributes ) => [
            'user_id' => $user->id,
        ] );
    }
}