<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Models\Rubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriptionAdminController extends Controller
{
    /**
     * Mostra la lista degli iscritti alla newsletter.
     */
    public function index(Request $request): View
    {
        $query = NewsletterSubscription::query()->with('rubrics')->orderBy('created_at', 'desc');

        $statusFilter = $request->input('status');
        if ($statusFilter === 'confirmed') {
            $query->whereNotNull('confirmed_at');
        } elseif ($statusFilter === 'pending') {
            $query->whereNull('confirmed_at');
        }

        $subscriptions = $query->paginate(20)->withQueryString();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Iscritti Newsletter']
        ];

        return view('admin.newsletter.index', compact('subscriptions', 'breadcrumbs', 'statusFilter'));
    }

    /**
     * Mostra il form per modificare le preferenze di un iscritto.
     */
    public function edit(NewsletterSubscription $newsletterSubscription): View
    {
        $allRubrics = Rubric::orderBy('name')->get();
        $subscribedRubricIds = $newsletterSubscription->rubrics->pluck('id')->toArray();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Iscritti Newsletter', 'url' => route('admin.newsletter-subscriptions.index')],
            ['label' => 'Modifica: ' . $newsletterSubscription->email]
        ];

        return view('admin.newsletter.edit', compact('newsletterSubscription', 'allRubrics', 'subscribedRubricIds', 'breadcrumbs'));
    }

    /**
     * Aggiorna le preferenze di un iscritto.
     */
    public function update(Request $request, NewsletterSubscription $newsletterSubscription): RedirectResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email,' . $newsletterSubscription->id,
            'rubriche_selezionate'   => 'nullable|array',
            'rubriche_selezionate.*' => 'exists:rubrics,id',
        ]);

        $newsletterSubscription->update(['email' => $validatedData['email']]);

        if (isset($validatedData['rubriche_selezionate'])) {
            $newsletterSubscription->rubrics()->sync($validatedData['rubriche_selezionate']);
        } else {
            // Se l'array non è presente, significa che sono state deselezionate tutte le rubriche
            $newsletterSubscription->rubrics()->sync([]);
        }

        return redirect()->route('admin.newsletter-subscriptions.index')
            ->with('success', "Iscrizione per {$newsletterSubscription->email} aggiornata con successo.");
    }

    /**
     * Rimuove un iscritto (lo disiscrive).
     */
    public function destroy(NewsletterSubscription $newsletterSubscription): RedirectResponse
    {
        $email = $newsletterSubscription->email;
        $newsletterSubscription->delete(); // Il cascade on delete nella pivot farà il resto

        return redirect()->route('admin.newsletter-subscriptions.index')
            ->with('success', "L'utente {$email} è stato disiscritto con successo.");
    }
}
