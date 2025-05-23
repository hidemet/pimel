<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User; // Per associare un autore
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
// Per lo slug e l'excerpt

class ArticleFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $title = fake()->unique()->sentence( rand( 5, 10 ) );
                                        // Titolo dell'articolo
        $bodyParagraphs = rand( 10, 25 ); // Numero di paragrafi per il corpo
        $body           = '';
        for ( $i = 0; $i < $bodyParagraphs; $i++ ) {
            $body .= '<p>' . fake()->paragraph( rand( 5, 15 ) ) . "</p>\n";
            if ( rand( 1, 5 ) === 1 && $i < $bodyParagraphs - 1 ) {
                // Aggiunge un sottotitolo H2-H4 occasionalmente
                $body .= '<h' . rand( 2, 4 ) . '>' . fake()->sentence( rand( 4, 8 ) )
                . '</h' . rand( 2, 4 ) . ">\n";
            }
        }

        $publishedAt = fake()->boolean( 85 ) ? fake()->dateTimeThisYear() :
        null; // 85% di probabilità di essere pubblicato quest'anno
        $status = $publishedAt ? ( ( new \DateTime( $publishedAt
                ->format( 'Y-m-d H:i:s' ) ) ) > now() ? 'scheduled' : 'published' ) : 'draft';
        // Status basato su published_at

        if ( $status === 'scheduled' ) {
            // se è schedulato, published_at deve essere nel futuro
            $publishedAt = fake()->dateTimeBetween( '+1 day', '+1 month' );
        } elseif ( $status === 'published' ) {
            // se è pubblicato, published_at deve essere nel passato
            $publishedAt = fake()->dateTimeBetween( '-1 year', '-1 day' );
        }

        // Calcolo approssimativo del tempo di lettura (200 parole al minuto)
        $wordCount   = str_word_count( strip_tags( $body ) );
        $readingTime = ceil( $wordCount / 200 );

        return [
            'user_id'          => User::inRandomOrder()->first()->id ??
            User::factory(), // Prende un utente casuale o ne crea uno se non ce ne sono
            'title'            => $title,
            // Lo slug è gestito dal mutator nel modello Article se vuoto
            // 'slug' => Str::slug($title),
            'excerpt'          => Str::words( strip_tags( fake()->paragraph( 5 ) ), 30,
                '...' ),                      // Excerpt generato
            'body'             => $body, // Corpo dell'articolo con paragrafi
            'image_path'       => null,
            // Per ora null, potremmo aggiungere logica per placeholder

            // 'image_path' => 'placeholders/article_image_' . rand(1, 5) . '.jpg', // Esempio se avessi placeholder
            'published_at'     => $publishedAt,
            'reading_time'     => $readingTime,
            'status'           => $status,
            'meta_description' => Str::limit( strip_tags( $body ), 155 ),
            // Meta description dall'inizio del corpo
            'meta_keywords'    => implode( ', ', fake()->words( rand( 5, 10 ) ) ),
            // Keywords fittizie
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure() {
        return $this->afterCreating( function ( Article $article ) {
            // Logica da eseguire dopo la creazione di un articolo

            // Ad esempio, associare rubriche. Lo faremo nel seeder per maggiore controllo.
        } );
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): static
    {
        return $this->state( fn( array $attributes ) => [
            'published_at' => fake()->dateTimePast(),
            'status'       => 'published',
        ] );
    }

    /**
     * Indicate that the article is a draft.
     */
    public function draft(): static
    {
        return $this->state( fn( array $attributes ) => [
            'published_at' => null,
            'status'       => 'draft',
        ] );
    }

    /**
     * Indicate that the article is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state( fn( array $attributes ) => [
            'published_at' => fake()->dateTimeBetween( '+1 day', '+1 month' ),
            'status'       => 'scheduled',
        ] );
    }

    /**
     * Assign a specific author to the article.
     */
    public function authoredBy( User $author ): static
    {
        return $this->state( fn( array $attributes ) => [
            'user_id' => $author->id,
        ] );
    }
}