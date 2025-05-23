<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
// Importa Carbon

class CommentFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $article = Article::inRandomOrder()->first() ?? Article::factory()
            ->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $isReply  = fake()->boolean( 25 );
        $parentId = null;

        if ( $isReply && $article->comments()->whereNull( 'parent_id' )->count()
            > 0 ) {
            $parentComment = $article->comments()->whereNull( 'parent_id' )
                ->inRandomOrder()->first();
            if ( $parentComment ) {
                $parentId = $parentComment->id;
            }
        }

        // Genera date/ore valide, tenendo conto dei cambi DST
        // Assicurati che la data di inizio sia valida
        $articlePublicationDate = $article->published_at ?? $article
            ->created_at;
        if ( !$articlePublicationDate instanceof Carbon ) {
            $articlePublicationDate = Carbon::parse( $articlePublicationDate );
        }

        // Genera un timestamp casuale tra la pubblicazione dell'articolo e ora

        // e poi formattalo in un modo che Carbon gestisca correttamente i fusi orari
        $randomTimestamp = fake()->dateTimeBetween( $articlePublicationDate,
            'now' )->getTimestamp();
        $createdAt = Carbon::createFromTimestamp( $randomTimestamp )
            ->format( 'Y-m-d H:i:s' );

        // Per updated_at, assicurati che sia uguale o successivo a created_at
        $updatedAtTimestamp = fake()->dateTimeBetween( $createdAt, 'now' )
            ->getTimestamp();
        $updatedAt = Carbon::createFromTimestamp( $updatedAtTimestamp )
            ->format( 'Y-m-d H:i:s' );

        return [
            'article_id'  => $article->id,
            'user_id'     => $user->id,
            'parent_id'   => $parentId,
            'body'        => fake()->paragraph( rand( 1, 5 ) ),
            'is_approved' => fake()->boolean( 90 ),
            'created_at'  => $createdAt,
            'updated_at'  => $updatedAt,
        ];
    }

    // ... (gli altri metodi della factory rimangono invariati)
    /**
     * Indicate that the comment is approved.
     */
    public function approved(): static
    {
        return $this->state( fn( array $attributes ) => [
            'is_approved' => true,
        ] );
    }

    /**
     * Indicate that the comment is not approved (pending).
     */
    public function pending(): static
    {
        return $this->state( fn( array $attributes ) => [
            'is_approved' => false,
        ] );
    }

    /**
     * Assign a specific article to the comment.
     */
    public function forArticle( Article $article ): static
    {
        $articlePublicationDate = $article->published_at ?? $article
            ->created_at;
        if ( !$articlePublicationDate instanceof Carbon ) {
            $articlePublicationDate = Carbon::parse( $articlePublicationDate );
        }
        $randomTimestamp = fake()->dateTimeBetween( $articlePublicationDate,
            'now' )->getTimestamp();
        $createdAt = Carbon::createFromTimestamp( $randomTimestamp )
            ->format( 'Y-m-d H:i:s' );

        return $this->state( fn( array $attributes ) => [
            'article_id' => $article->id,
            'created_at' => $createdAt,
            // Aggiorna anche created_at quando si imposta l'articolo
            'updated_at' => Carbon::createFromTimestamp( fake()
                    ->dateTimeBetween( $createdAt, 'now' )->getTimestamp() )->format( 'Y-m-d H:i:s' ),
        ] );
    }

    /**
     * Assign a specific user (author) to the comment.
     */
    public function byUser( User $user ): static
    {
        return $this->state( fn( array $attributes ) => [
            'user_id' => $user->id,
        ] );
    }

    /**
     * Make this comment a reply to another comment.
     */
    public function replyTo( Comment $parentComment ): static
    {
        $parentCommentCreatedAt = $parentComment->created_at;
        if ( !$parentCommentCreatedAt instanceof Carbon ) {
            $parentCommentCreatedAt = Carbon::parse( $parentCommentCreatedAt );
        }
        $randomTimestamp = fake()->dateTimeBetween( $parentCommentCreatedAt,
            'now' )->getTimestamp();
        $createdAt = Carbon::createFromTimestamp( $randomTimestamp )
            ->format( 'Y-m-d H:i:s' );

        return $this->state( fn( array $attributes ) => [
            'article_id' => $parentComment->article_id,
            'parent_id'  => $parentComment->id,
            'created_at' => $createdAt,
            // La risposta deve essere dopo il commento padre
            'updated_at' => Carbon::createFromTimestamp( fake()
                    ->dateTimeBetween( $createdAt, 'now' )->getTimestamp() )->format( 'Y-m-d H:i:s' ),
        ] );
    }
}