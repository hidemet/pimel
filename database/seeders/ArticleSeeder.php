<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Article::truncate(); // Opzionale

        // DB::table('article_rubric')->truncate(); // Opzionale, per pulire la tabella pivot

        // Recupera l'utente Manuela Donati (admin) o il primo utente admin disponibile
        $author = User::where( 'email', 'manuela.donati@pimel.it' )->first();
        if ( !$author ) {
            $author = User::where( 'role', 'admin' )->first() ??
            User::factory()->admin()->create( ['name' =>
                'Autore Admin Default'] );
        }

        // Recupera tutte le rubriche
        $rubrics = Rubric::all();

        if ( $rubrics->isEmpty() ) {
            $this->command
                ->warn( 'Nessuna rubrica trovata. Esegui prima RubricSeeder.'
                );

            // Potresti voler chiamare RubricSeeder qui se non è stato eseguito
            // $this->call(RubricSeeder::class);
            // $rubrics = Rubric::all();
            // if ($rubrics->isEmpty()) {

            //     $this->command->error('Impossibile creare articoli senza rubriche.');
            //     return;
            // }
            return;
        }

                                // Crea un certo numero di articoli
        $numberOfArticles = 25; // Quanti articoli vuoi creare

        for ( $i = 0; $i < $numberOfArticles; $i++ ) {

            // Scegli casualmente se l'articolo sarà scritto da Manuela o da un altro utente (se esistono)
            $currentAuthor = User::inRandomOrder()->first() ?? $author;
            // Prende un utente casuale o Manuela se non ci sono altri

            $article = Article::factory()
                ->authoredBy( $currentAuthor ) // Imposta l'autore
                ->create();

            // Associa da 1 a 3 rubriche casuali all'articolo
            $rubricsToAttach = $rubrics->random( rand( 1, min( 3, $rubrics
                    ->count() ) ) );
            $article->rubrics()->attach( $rubricsToAttach->pluck( 'id' )
                    ->toArray() );
        }

        // Crea alcuni articoli specifici per rubriche specifiche (opzionale ma utile)
        $this->createSpecificArticles( $author, $rubrics );
    }

    private function createSpecificArticles( User $defaultAuthor, $allRubrics
    ) {
        $specificArticlesData = [
            [
                'title'        =>
                'Comprendere i Capricci dei Bambini: Strategie Efficaci',
                'rubric_slugs' => ['sviluppo-0-6-anni',
                    'genitorialita-consapevole'],
                'status'       => 'published',
                'body_hint'    =>

                'Esploriamo le cause dei capricci e come affrontarli con empatia...',
                // Per dare un'idea del contenuto
            ],
            [
                'title'        =>
                'Navigare le Complessità dell\'Adolescenza Moderna',
                'rubric_slugs' => ['adolescenza', 'genitorialita-consapevole'],
                'status'       => 'published',
                'body_hint'    =>

                'Sfide uniche che gli adolescenti affrontano oggi, dai social media all\'identità...',
            ],
            [
                'title'        =>
                'Il Ruolo del Gioco nello Sviluppo Cognitivo 0-3 anni',
                'rubric_slugs' => ['sviluppo-0-6-anni'],
                'status'       => 'published',
                'body_hint'    =>

                'L\'importanza del gioco libero e strutturato per le capacità cognitive...',
            ],
            [
                'title'        =>
                'DSA a Scuola: Guida per Insegnanti e Genitori',
                'rubric_slugs' => ['dsa-bes', 'pedagogia-scuola',
                    'mondo-educatori'],
                'status'       => 'draft', // Esempio di bozza
                'body_hint'    =>
                'Strumenti compensativi, PDP e strategie inclusive...',
            ],
            [
                'title'        =>

                'Pedagogia di Genere: Educare alle Differenze e all\'Uguaglianza',
                'rubric_slugs' => ['pedagogia-di-genere'],
                'status'       => 'scheduled', // Esempio di schedulato
                'body_hint'    =>

                'Superare gli stereotipi e promuovere una cultura del rispetto...',
            ],
            [
                'title'        =>
                'Recensione: "Il Bambino Competente" di Jesper Juul',
                'rubric_slugs' => ['recensioni-libri-film-e-risorse',
                    'genitorialita-consapevole'],
                'status'       => 'published',
                'body_hint'    =>

                'Un classico della letteratura pedagogica per genitori ed educatori...',
            ],
        ];

        foreach ( $specificArticlesData as $data ) {
            $article = Article::factory()
                ->authoredBy( $defaultAuthor )
            // O scegli un autore specifico se necessario
                ->state( function ( array $attributes ) use ( $data ) {
                    // Usa state per sovrascrivere il titolo
                    return [
                        'title'  => $data['title'],
                        'status' => $data['status'],

                        // Potremmo aggiungere il 'body_hint' all'inizio del body generato dalla factory

                        // 'body' => '<p><em>' . $data['body_hint'] . '</em></p>' . Article::factory()->definition()['body'], // Esempio
                    ];
                } )
                ->create();

            $rubricsForArticle = $allRubrics->whereIn( 'slug',
                $data['rubric_slugs'] );
            if ( $rubricsForArticle->isNotEmpty() ) {
                $article->rubrics()->attach( $rubricsForArticle->pluck( 'id' )
                        ->toArray() );
            } else {
                $this->command->warn( "Rubriche non trovate per gli slug: " .
                    implode( ', ', $data['rubric_slugs'] ) .
                    " per l'articolo '{$data['title']}'" );
            }
        }
    }
}