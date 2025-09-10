<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::published()->get();
        $users = User::all();

        if ($articles->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Dati insufficienti per creare commenti.');
            return;
        }

        $this->createRandomComments($articles, $users);
        $this->createSpecificComments($articles, $users);

        $this->command->info('Commenti creati con successo.');
    }

    private function createRandomComments($articles, $users): void
    {
        foreach (range(1, 150) as $i) {
            Comment::factory()
                ->forArticle($articles->random()->id)
                ->byUser($users->random()->id)
                ->create();
        }
    }

    private function createSpecificComments($articles, $users): void
    {
        $firstArticle = $articles->sortBy('published_at')->first();
        if (!$firstArticle) return;

        $specificComments = [
            'Questo è il primo commento principale su questo articolo!',
            'Ottima osservazione!',
            'Concordo pienamente con il commento precedente.',
        ];

        foreach ($specificComments as $body) {
            Comment::factory()
                ->forArticle($firstArticle->id)
                ->byUser($users->random()->id)
                ->withBody($body)
                ->approved()
                ->recent()
                ->create();
        }
    }
}
