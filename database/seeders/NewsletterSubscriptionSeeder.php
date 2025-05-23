<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscription;
use App\Models\Rubric;
use Illuminate\Database\Seeder;
// Per la tabella pivot

class NewsletterSubscriptionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // NewsletterSubscription::truncate(); // Opzionale

        // DB::table('rubric_newsletter_subscription')->truncate(); // Opzionale, pulisce la tabella pivot

        $rubrics = Rubric::all();

        if ( $rubrics->isEmpty() ) {
            $this->command
                ->warn( 'Nessuna rubrica trovata. Impossibile associare preferenze alle iscrizioni newsletter.' );

            // Considera se creare rubriche qui o semplicemente creare iscrizioni senza preferenze.
            // Per ora, creiamo iscrizioni anche senza rubriche.
        }

        $numberOfSubscriptions = 70; // Numero di iscrizioni da creare

        NewsletterSubscription::factory()
            ->count( $numberOfSubscriptions )
            ->create()
            ->each( function ( NewsletterSubscription $subscription ) use
                ( $rubrics ) {

                    // Associa rubriche solo se l'iscrizione è confermata e ci sono rubriche disponibili
                    if ( $subscription->isConfirmed() && $rubrics->isNotEmpty() ) {

                        // Ogni iscritto segue da 0 a 5 rubriche (o tutte se "select all" fosse una logica)

                        // Per semplicità, qui scegliamo un numero casuale di rubriche.

                        // Se un utente seleziona "tutte", potresti avere una logica diversa nel controller.
                        $numberOfPreferences = rand( 0, min( 5, $rubrics->count() ) );
                        if ( $numberOfPreferences > 0 ) {
                            $rubricsToFollow = $rubrics
                                ->random( $numberOfPreferences );
                            $subscription->rubrics()->attach( $rubricsToFollow
                                    ->pluck( 'id' )->toArray() );
                        }

                        // Se numberOfPreferences è 0, l'utente è iscritto ma non ha preferenze specifiche (riceve tutto o nulla a seconda della tua logica di invio)
                    }
                } );

        $this->command
            ->info( "Create {$numberOfSubscriptions} iscrizioni alla newsletter con preferenze casuali (per i confermati)." );
    }
}