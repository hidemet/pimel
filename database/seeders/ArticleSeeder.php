<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    private const AUTHOR_EMAIL = 'manudona82@gmail.com';

    private const TOTAL_ARTICLES = 25;

    public function run(): void
    {
        $author = $this->getAuthor();
        $rubrics = $this->getRubrics();

        $this->createRandomArticles($author, $rubrics);

        $this->command->info('Creati '.self::TOTAL_ARTICLES.' articoli con successo.');
    }

    private function getAuthor(): User
    {
        return User::where('email', self::AUTHOR_EMAIL)->firstOrFail();
    }

    private function getRubrics(): Collection
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
        $this->command->info('Creazione articoli...');

        foreach (range(1, self::TOTAL_ARTICLES) as $i) {
            Article::factory()
                ->byAuthor($author->id)
                ->inRubric($rubrics->random()->id)
                ->create();
        }
    }
}
