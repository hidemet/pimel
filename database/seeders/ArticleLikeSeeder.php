<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleLikeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // ArticleLike::truncate(); // Opzionale

        $articles = Article::where( 'status', 'published' )->get();
        $users    = User::all();

        if ( $articles->isEmpty() || $users->isEmpty() ) {
            $this->command
                ->warn(
                    'Nessun articolo pubblicato o utente trovato. Impossibile creare "mi piace".' );
            return;
        }

        $likesToCreate = 300;
        // Numero desiderato di "mi piace" da creare in totale

        for ( $i = 0; $i < $likesToCreate; $i++ ) {
            $article = $articles->random();
            $user    = $users->random();

            try {
                ArticleLike::factory()
                    ->forArticle( $article )
                    ->byUser( $user )
                // created_at è già gestito nella factory forArticle
                    ->create();
            } catch ( \Illuminate\Database\QueryException $e ) {

                // Codice di errore per violazione di vincolo univoco (varia a seconda del DB)
                // Per SQLite è 19, per MySQL è 1062, per PostgreSQL è 23505
                $sqlErrorCode = $e->errorInfo[1];
                if ( $sqlErrorCode == 19 || $sqlErrorCode == 1062 ||
                    $sqlErrorCode == 23505 ) {

                    // È una violazione di unicità (l'utente ha già messo "mi piace" a questo articolo), ignorala e continua

                    // $this->command->info("Like già esistente per article_id: {$article->id} e user_id: {$user->id}. Skippato.");
                } else {
                    // Altro errore SQL, rilancialo o gestiscilo

                    // $this->command->error("Errore SQL durante la creazione del like: " . $e->getMessage());
                    // throw $e; // Se vuoi interrompere il seeder
                }
            }
        }

        $this->command
            ->info(
                "Tentativo di creazione di {$likesToCreate} 'mi piace' completato." );
    }
}