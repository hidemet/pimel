<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\Rubric; // Assicurati che sia importato
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Assicurati che sia importato
use Illuminate\View\View;          // Assicurati che sia importato


// use Illuminate\Support\Facades\Mail;
// use App\Mail\ConfirmNewsletterSubscription; // Creeremo più avanti

class NewsletterSubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'email'                  =>
            'required|email|max:255|unique:newsletter_subscriptions,email',
            'rubriche_selezionate'   => 'nullable|array',
            'rubriche_selezionate.*' => 'exists:rubrics,id',
            'select_all_rubriche'    => 'nullable|boolean',
        ], [
            'email.unique'                  =>
            'Questo indirizzo email è già iscritto alla newsletter.',
            'rubriche_selezionate.*.exists' =>
            'Una delle rubriche selezionate non è valida.',
        ]);

        $subscriptionData = collect($validatedData)->except([
            'select_all_rubriche',
            'rubriche_selezionate'
        ])->toArray();

        $subscription = NewsletterSubscription::create($subscriptionData);

        // Gestione preferenze rubriche
        if ($subscription) {
            if ($request->boolean('select_all_rubriche')) {
                $allRubricIds = Rubric::pluck('id')->toArray();
                $subscription->rubrics()->sync($allRubricIds);
            } elseif (!empty($validatedData['rubriche_selezionate'])) {
                $subscription->rubrics()
                    ->sync($validatedData['rubriche_selezionate']);
            } else {
                // Nessuna rubrica selezionata, o deselezionato "tutti"

                // $subscription->rubrics()->detach(); // Se vuoi rimuovere tutte le associazioni esistenti
            }
        }

        // Opzionale: invia email di conferma (con double opt-in o solo notifica)

        // Mail::to($subscription->email)->send(new ConfirmNewsletterSubscription($subscription));

        return back()->with(
            'success',
            'Grazie per esserti iscritto alla newsletter!'
        );
    }

    // public function edit( Request $request ): RedirectResponse {
    //     $user = $request->user();
    //     $subscription = $user->newsletterSubscription::where( 'email',
    //         $user->email )->first();
    //     if ( !$subscription ) {
    //         return redirect()->route( 'profile.edit' )
    //             ->with( 'info.', 'Non risulti iscritto alla newsletter.' );
    // }
    // $allRubrics = Rubric::orderBy( 'name' )->get();
    //     $selectedRubrics = $subscription->rubrics->pluck( 'id' )->toArray(): [];

    //     return view( 'profile.newsletter-preferences', compact( 'subscription',
    //         'allRubrics', 'selectedRubricIds' ) );
    // }

    // public function update( Request $request ): RedirectResponse {
    //     $user = $request->user();
    //     $subscription = $user->newsletterSubscription::where( 'email',
    //         $user->email )->firstOrFail();

    //     if ( !$subscription ) {
    //         return redirect()->route( 'profile.edit' )
    //             ->with( 'info', 'Non risulti iscritto alla newsletter.' );
    //     }

    //     $validatedData = $request->validate([
    //     'rubriche_selezionate'   => 'nullable|array',
    //     'rubriche_selezionate.*' => 'exists:rubrics,id',
    //     'select_all_rubriche'    => 'nullable|boolean',
    //     ]);

    //     if ($request->boolean('select_all_rubriche')) {
    //     $allRubricIds = Rubric::pluck('id')->toArray();
    //     $subscription->rubrics()->sync($allRubricIds);
    //     } elseif (!empty($validatedData['rubriche_selezionate'])) {
    //         $subscription->rubrics()->sync($validatedData['rubriche_selezionate']);
    //     } else {
    //         $subscription->rubrics()->detach(); // Se non seleziona nulla, rimuove tutte le preferenze
    //     }
    //     return redirect()->route('profile.newsletter.edit') // o 'profile.edit'
    //                     ->with('success', 'Preferenze newsletter aggiornate con successo.');



    // }

    // public function destroy( Request $request ): RedirectResponse {
    //    $user = $request->user();
    //     $subscription = NewsletterSubscription::where('email', $user->email)->first();
    //     if ($subscription) {
    //         $subscription->delete(); // Questo triggererà anche il detach delle rubriche grazie al cascade on delete (se impostato nel DB) o manualmente $subscription->rubrics()->detach();
    //         return redirect()->route('profile.edit') // o dove ha senso
    //                         ->with('success', 'La tua iscrizione alla newsletter è stata annullata.');
    //     }
    //     return redirect()->route('profile.edit')->with('error', 'Non è stato possibile annullare l\'iscrizione o non eri iscritto.');
    // }


    public function editPreferences(Request $request): View // Rinominiamo per chiarezza rispetto a un eventuale 'edit' per admin
    {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->first();

        // Se l'utente non è iscritto, potremmo reindirizzarlo o mostrare un messaggio specifico
        // Per ora, se $subscription è null, la vista lo gestirà.
        // Alternativamente:
        // if (!$subscription) {
        //     return redirect()->route('profile.edit')->with('toast', [
        //         'type' => 'info',
        //         'title' => 'Newsletter',
        //         'message' => 'Non risulti attualmente iscritto alla newsletter con questa email.'
        //     ]);
        // }

        $allRubrics = Rubric::orderBy('name')->get();
        $subscribedRubricIds = [];
        if ($subscription) {
            $subscribedRubricIds = $subscription->rubrics->pluck('id')->toArray();
        }

        return view('profile.newsletter-preferences', compact('subscription', 'allRubrics', 'subscribedRubricIds'));
    }

    /**
     * Aggiorna le preferenze della newsletter per l'utente autenticato.
     */
    public function updatePreferences(Request $request): RedirectResponse // Rinominiamo per chiarezza
    {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->firstOrFail(); // Fallisce se non trovato, che è ok qui

        $validatedData = $request->validate([
            'rubriche_selezionate'   => 'nullable|array',
            'rubriche_selezionate.*' => 'exists:rubrics,id', // Valida che gli ID delle rubriche esistano
            'select_all_rubriche'    => 'nullable|boolean',
        ], [
            'rubriche_selezionate.*.exists' => 'Una o più rubriche selezionate non sono valide.',
        ]);

        if ($request->boolean('select_all_rubriche')) {
            $allRubricIds = Rubric::pluck('id')->toArray();
            $subscription->rubrics()->sync($allRubricIds);
        } elseif (isset($validatedData['rubriche_selezionate'])) { // Controlla se la chiave esiste
            $subscription->rubrics()->sync($validatedData['rubriche_selezionate']);
        } else {
            // Nessuna rubrica selezionata e "select_all_rubriche" non è true, quindi deseleziona tutto
            $subscription->rubrics()->detach();
        }

        return redirect()->route('profile.newsletter.edit') // Reindirizza alla pagina di modifica preferenze
            ->with('toast', [
                'type' => 'success',
                'title' => 'Preferenze Aggiornate',
                'message' => 'Le tue preferenze per la newsletter sono state salvate.'
            ]);
    }

    /**
     * Annulla l'iscrizione alla newsletter per l'utente autenticato.
     */
    public function destroySubscription(Request $request): RedirectResponse // Rinominiamo per chiarezza
    {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->first();

        if ($subscription) {
            // Prima stacca tutte le rubriche associate (best practice, anche se il DB potrebbe farlo con cascade)
            $subscription->rubrics()->detach();
            $subscription->delete();

            return redirect()->route('profile.edit') // O una pagina di conferma
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Iscrizione Annullata',
                    'message' => 'La tua iscrizione alla newsletter è stata annullata con successo.'
                ]);
        }

        return redirect()->route('profile.edit')
            ->with('toast', [
                'type' => 'error',
                'title' => 'Errore',
                'message' => 'Non è stato possibile annullare l\'iscrizione o non risultavi iscritto.'
            ]);
    }
}
