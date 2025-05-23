<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Comment::truncate(); // Opzionale

        $articles = Article::where( 'status', 'published' )->get();
        // Prendi solo articoli pubblicati
        $users = User::all();

        if ( $articles->isEmpty() || $users->isEmpty() ) {
            $this->command
                ->warn( 'Nessun articolo pubblicato o utente trovato. Impossibile creare commenti.' );
            return;
        }

        $numberOfCommentsToCreate = 150; // Numero totale di commenti da creare

        for ( $i = 0; $i < $numberOfCommentsToCreate; $i++ ) {
            $article = $articles->random();
                                      // Scegli un articolo pubblicato a caso
            $user = $users->random(); // Scegli un utente a caso

            // Crea un commento di primo livello o una risposta
            // La logica per decidere se è una risposta è già nella factory
            Comment::factory()
                ->forArticle( $article )
                ->byUser( $user )
                ->create( [

                    // Assicurati che la data del commento sia successiva a quella dell'articolo
                    'created_at' => fake()->dateTimeBetween( $article
                            ->published_at ?? $article->created_at, 'now' ),
                    ] );
            }

            // Esempio di come creare una catena di risposte per un articolo specifico (se esiste)
            $firstArticle = Article::orderBy( 'published_at', 'asc' )->first();
            if ( $firstArticle ) {
                $author1 = $users->random();
                $author2 = $users->random();
                $author3 = $users->random();

                $comment1 = Comment::factory()
                    ->forArticle( $firstArticle )
                    ->byUser( $author1 )
                    ->approved() // Assicurati sia approvato
                    ->create( [
                        'body'       =>
                        'Questo è il primo commento principale su questo articolo!',
                        'created_at' => fake()->dateTimeBetween( $firstArticle
                            ->published_at, now()->subDays( 5 ) ),
                ] );

            if ( $comment1 ) {
                $reply1 = Comment::factory()
                    ->forArticle( $firstArticle ) // Stesso articolo
                    ->byUser( $author2 )
                    ->replyTo( $comment1 ) // Risposta a comment1
                    ->approved()
                    ->create( [
                        'body'       => 'Ottima osservazione, ' . $author1->name .
                        '!',
                        'created_at' => fake()->dateTimeBetween( $comment1
                                ->created_at, now()->subDays( 4 ) ),
                    ] );

                if ( $reply1 ) {
                    Comment::factory()
                        ->forArticle( $firstArticle )
                        ->byUser( $author3 )
                        ->replyTo( $reply1 ) // Risposta a reply1
                        ->approved()
                        ->create( [
                            'body'       => 'Concordo pienamente con ' . $author2
                                ->name,
                            'created_at' => fake()->dateTimeBetween( $reply1
                                    ->created_at, now()->subDays( 3 ) ),
                        ] );
                }
            }
        }
    }
}