<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // ContactMessage::truncate(); // Opzionale

        $numberOfMessages = 50; // Quanti messaggi di contatto vuoi creare

        ContactMessage::factory()->count( $numberOfMessages )->create();

        $this->command
            ->info( "Creati {$numberOfMessages} messaggi di contatto fittizi." );
    }
}