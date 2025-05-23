<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\Rubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Mail;
// use App\Mail\ConfirmNewsletterSubscription; // Creeremo più avanti

class NewsletterSubscriptionController extends Controller {
    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ): RedirectResponse {
        $validatedData = $request->validate( [
            'email'                  =>
            'required|email|max:255|unique:newsletter_subscriptions,email',
            'rubriche_selezionate'   => 'nullable|array',
            // Array di ID o slug delle rubriche
            'rubriche_selezionate.*' => 'exists:rubrics,id',
            // Assicurati che ogni rubrica esista (se passi ID)
            'select_all_rubriche'    => 'nullable|boolean',
            'privacy'                => 'required|accepted',
        ], [
            'email.unique'                  =>
            'Questo indirizzo email è già iscritto alla newsletter.',
            'rubriche_selezionate.*.exists' =>
            'Una delle rubriche selezionate non è valida.',
        ] );

        // Rimuovi 'privacy' e 'select_all_rubriche' dai dati da salvare per la sottoscrizione
        $subscriptionData = collect( $validatedData )->except( ['privacy',
            'select_all_rubriche', 'rubriche_selezionate'] )->toArray();

        // Per ora, confermiamo direttamente. In futuro potresti implementare double opt-in.
        $subscriptionData['token'] = null;
        // Str::random(60); // Se volessi un token per conferma
        $subscriptionData['confirmed_at'] = now();

        $subscription = NewsletterSubscription::create( $subscriptionData );

        // Gestione preferenze rubriche
        if ( $subscription ) {
            if ( $request->boolean( 'select_all_rubriche' ) ) {
                $allRubricIds = Rubric::pluck( 'id' )->toArray();
                $subscription->rubrics()->sync( $allRubricIds );
            } elseif ( !empty( $validatedData['rubriche_selezionate'] ) ) {
                $subscription->rubrics()
                    ->sync( $validatedData['rubriche_selezionate'] );
            } else {
                // Nessuna rubrica selezionata, o deselezionato "tutti"

                // $subscription->rubrics()->detach(); // Se vuoi rimuovere tutte le associazioni esistenti
            }
        }

        // Opzionale: invia email di conferma (con double opt-in o solo notifica)

        // Mail::to($subscription->email)->send(new ConfirmNewsletterSubscription($subscription));

        return back()->with( 'success',
            'Grazie per esserti iscritto alla newsletter!' );
    }
}