<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    private const AUTHOR_EMAIL = 'manudona82@gmail.com';

    private const ARTICLE_COUNTS = [
        'published' => 15,
        'draft' => 5,
        'archived' => 2,
    ];

    private const SPECIFIC_ARTICLES = [
        ['title' => 'Comprendere i Capricci dei Bambini: Strategie Efficaci', 'rubric_slug' => 'sviluppo-0-6-anni', 'status' => 'published'],
        ['title' => 'Navigare le Complessità dell\'Adolescenza Moderna', 'rubric_slug' => 'adolescenza', 'status' => 'published'],
        ['title' => 'Il Ruolo del Gioco nello Sviluppo Cognitivo 0-3 anni', 'rubric_slug' => 'sviluppo-0-6-anni', 'status' => 'published'],
        ['title' => 'DSA a Scuola: Guida per Insegnanti e Genitori', 'rubric_slug' => 'dsa-bes', 'status' => 'draft'],
        ['title' => 'Pedagogia di Genere: Educare alle Differenze e all\'Uguaglianza', 'rubric_slug' => 'pedagogia-di-genere', 'status' => 'draft'],
        ['title' => 'Recensione: "Il Bambino Competente" di Jesper Juul', 'rubric_slug' => 'recensioni-libri-film-e-risorse', 'status' => 'published'],
    ];

    public function run(): void
    {
        $author = $this->getAuthor();
        $rubrics = $this->getRubrics();

        $this->createRandomArticles($author, $rubrics);
        $this->createSpecificArticles($author, $rubrics);

        $totalArticles = array_sum(self::ARTICLE_COUNTS) + count(self::SPECIFIC_ARTICLES);
        $this->command->info("Creati {$totalArticles} articoli totali con successo.");
    }

    private function getAuthor(): User
    {
        return User::where('email', self::AUTHOR_EMAIL)->firstOrFail();
    }

    private function getRubrics(): \Illuminate\Database\Eloquent\Collection
    {
        $rubrics = Rubric::all();

        if ($rubrics->isEmpty()) {
            $this->command->warn('Nessuna rubrica trovata. Esegui prima RubricSeeder.');
            exit(1);
        }

        return $rubrics;
    }

    private function createRandomArticles(User $author, $rubrics): void
    {
        $this->command->info('Creazione articoli casuali...');

        foreach (self::ARTICLE_COUNTS as $status => $count) {
            foreach (range(1, $count) as $i) {
                Article::factory()
                    ->{$status}()
                    ->byAuthor($author->id)
                    ->inRubric($rubrics->random()->id)
                    ->create();
            }
        }
    }

    private function createSpecificArticles(User $author, $rubrics): void
    {
        $this->command->info('Creazione articoli specifici...');

        foreach (self::SPECIFIC_ARTICLES as $articleData) {
            $rubric = $rubrics->firstWhere('slug', $articleData['rubric_slug']);

            if (!$rubric) {
                $this->command->warn("Rubrica '{$articleData['rubric_slug']}' non trovata per '{$articleData['title']}'");
                continue;
            }

            Article::factory()
                ->{$articleData['status']}()
                ->byAuthor($author->id)
                ->inRubric($rubric->id)
                ->withTitle($articleData['title'])
                ->create();
        }
    }
}
