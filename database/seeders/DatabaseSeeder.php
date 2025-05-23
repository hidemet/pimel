<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Puoi commentarlo se non lo usi

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // Creare l'utente amministratore specifico
        User::factory()->manuelaDonati()->create();

                                    // Creare alcuni utenti fittizi generici
        User::factory( 5 )->create(); // Crea 5 utenti 'user' fittizi

        // Creare un utente admin generico aggiuntivo (oltre a Manuela)
        User::factory()->admin()->create( [
            'name'     => 'Nicholas Dumas',
            'email'    => 'n.dumas@studenti.unibs.it',
            'password' => bcrypt( 'PimelAdmin!2024' ),
        ] );

        // Chiamare gli altri seeder
        $this->call( [
            RubricSeeder::class,
            // Aggiungeremo qui gli altri seeder man mano che li creiamo
            ServiceSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            ArticleLikeSeeder::class,
            NewsletterSubscriptionSeeder::class,
            ContactMessageSeeder::class,
        ] );
    }
}