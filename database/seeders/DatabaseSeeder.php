<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::factory()->manuelaDonati()->create();
        User::factory()->nicholasDumas()->create();
        User::factory(5)->create();

        // l'ordine delle chiamate ai seeder è importante perché alcuni dati dipendono da altri
        // es. prima creiamo le rubriche e gli utenti e poi gli articoli così possiamo popolare i foreign key correttamente.
        $this->call([
            RubricSeeder::class,
            TargetCategorySeeder::class,
            ServiceSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            ArticleLikeSeeder::class,
            NewsletterSubscriptionSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}
