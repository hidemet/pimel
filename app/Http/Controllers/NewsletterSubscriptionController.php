<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Http\Requests\UpdateNewsletterPreferencesRequest;
use App\Models\NewsletterSubscription;
use App\Models\Rubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriptionController extends Controller {
    public function store(StoreNewsletterSubscriptionRequest $request): RedirectResponse {
        $validated = $request->validated();

        $subscription = NewsletterSubscription::create([
            'email' => $validated['email']
        ]);

        $this->syncRubrics($subscription, $validated);

        return back()->with('success', 'Grazie per esserti iscritto alla newsletter!');
    }

    public function editPreferences(Request $request): View {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->first();
        $allRubrics = Rubric::orderBy('name')->get();
        $subscribedRubricIds = $subscription?->rubrics->pluck('id')->toArray() ?? [];

        return view('profile.newsletter-preferences', compact('subscription', 'allRubrics', 'subscribedRubricIds'));
    }

    public function updatePreferences(UpdateNewsletterPreferencesRequest $request): RedirectResponse {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->firstOrFail();

        $this->syncRubrics($subscription, $request->validated());

        return redirect()->route('profile.newsletter.edit')
            ->with('success', 'Le tue preferenze per la newsletter sono state aggiornate con successo!');
    }

    public function destroySubscription(Request $request): RedirectResponse {
        $user = $request->user();
        $subscription = NewsletterSubscription::where('email', $user->email)->first();

        if ($subscription) {
            $subscription->rubrics()->detach();
            $subscription->delete();

            return redirect()->route('profile.edit')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Iscrizione Annullata',
                    'message' => 'La tua iscrizione alla newsletter è stata annullata con successo.',
                ]);
        }

        return redirect()->route('profile.edit')
            ->with('toast', [
                'type' => 'error',
                'title' => 'Errore',
                'message' => 'Non è stato possibile annullare l\'iscrizione o non risultavi iscritto.',
            ]);
    }

    private function syncRubrics(NewsletterSubscription $subscription, array $validated): void {
        if ($validated['select_all_rubriche'] ?? false) {
            $subscription->rubrics()->sync(Rubric::pluck('id'));
        } elseif (!empty($validated['rubriche_selezionate'])) {
            $subscription->rubrics()->sync($validated['rubriche_selezionate']);
        } else {
            $subscription->rubrics()->detach();
        }
    }
}
