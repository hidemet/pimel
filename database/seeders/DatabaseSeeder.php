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