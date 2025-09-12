<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NicholasDumasSeeder extends Seeder {
    public function run(): void {
        // Verifica se l'utente esiste già
        $existingUser = User::where('email', 'nicholas.dumas.001@gmail.com')->first();

        if (!$existingUser) {
            User::factory()->nicholasDumas()->create();
            $this->command->info('Utente Nicholas Dumas creato con successo.');
        } else {
            $this->command->info('Utente Nicholas Dumas esiste già.');
        }
    }
}
