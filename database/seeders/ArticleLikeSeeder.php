<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

class ArticleLikeSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::published()->get();
        $users = User::all();

        if ($articles->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Nessun articolo pubblicato o utente trovato. Impossibile creare "mi piace".');

            return;
        }

        $likesToCreate = 300;

        for ($i = 0; $i < $likesToCreate; ++$i) {
            $article = $articles->random();
            $user = $users->random();
            $createdAt = fake()->dateTimeBetween(
                $article->published_at ?? $article->created_at,
                'now'
            );

            try {
                ArticleLike::factory()
                    ->forArticle($article->id)
                    ->byUser($user->id)
                    ->onDate($createdAt)
                    ->create();
            } catch (QueryException $e) {
                $sqlErrorCode = $e->errorInfo[1];
                if (in_array($sqlErrorCode, [19, 1062, 23505])) {
                    continue;
                }
            }
        }

        $this->command->info("Tentativo di creazione di {$likesToCreate} 'mi piace' completato.");
    }
}
