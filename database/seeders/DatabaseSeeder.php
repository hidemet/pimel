<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->manuelaDonati()->create();
        User::factory(5)->create();
        User::factory()->admin()->create([
            'name'     => 'Nicholas Dumas',
            'email'    => 'n.dumas@studenti.unibs.it',
            'password' => bcrypt('PimelAdmin!2024'),
        ]);

        $this->call([
            RubricSeeder::class,
            TargetCategorySeeder::class, // AGGIUNTO: Prima di ServiceSeeder
            ServiceSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            ArticleLikeSeeder::class,
            NewsletterSubscriptionSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}