<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User|null  $user L'utente autenticato, o null se visitatore.
     * @param  \App\Models\Article  $article L'articolo che si sta cercando di visualizzare.
     * @return bool
     */
    public function view(?User $user, Article $article): bool
    {
        // Normalizza eventuali vecchi stati inglesi non migrati
        $legacyMap = [
            'published' => 'Pubblicato',
            'draft' => 'Bozza',
            'archived' => 'Archiviato',
            'scheduled' => 'Programmata',
        ];
        if (isset($legacyMap[$article->status])) {
            $article->status = $legacyMap[$article->status];
        }

        // Se l'articolo è pubblicato, tutti possono vederlo.
        if ($article->status === 'Pubblicato') {
            return true;
        }

        // Se l'articolo NON è pubblicato:
        // Solo gli utenti autenticati che sono amministratori possono vederlo.
        return $user?->isAdmin() ?? false;
    }

    /**
     * Determine whether the user can create models.
     * Solo gli admin possono creare articoli.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Solo gli admin possono aggiornare articoli (o l'autore, se lo permettessi).
     */
    public function update(User $user, Article $article): bool
    {
        // Esempio: solo admin può aggiornare QUALSIASI articolo
        return $user->isAdmin();
        // Oppure:
        // return $user->isAdmin() || $user->id === $article->user_id; // Admin o l'autore
    }

    /**
     * Determine whether the user can delete the model.
     * Solo gli admin possono eliminare articoli.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * (Se usi soft deletes)
     */
    // public function restore(User $user, Article $article): bool
    // {
    //     return $user->isAdmin();
    // }

    /**
     * Determine whether the user can permanently delete the model.
     * (Se usi soft deletes)
     */
    // public function forceDelete(User $user, Article $article): bool
    // {
    //     return $user->isAdmin();
    // }
}
