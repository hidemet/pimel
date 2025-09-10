<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscription;
use App\Models\Rubric;
use Illuminate\Database\Seeder;

class NewsletterSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $rubrics = Rubric::all();

        if ($rubrics->isEmpty()) {
            $this->command->warn('Nessuna rubrica trovata. Creazione iscrizioni senza preferenze.');
        }

        NewsletterSubscription::factory(70)
            ->create()
            ->each(function ($subscription) use ($rubrics) {
                if ($rubrics->isNotEmpty()) {
                    $numberOfPreferences = rand(0, min(5, $rubrics->count()));
                    if ($numberOfPreferences > 0) {
                        $subscription->rubrics()->attach(
                            $rubrics->random($numberOfPreferences)->pluck('id')
                        );
                    }
                }
            });

        $this->command->info('Iscrizioni newsletter create con successo.');
    }
}
