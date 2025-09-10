<?php

namespace App\Actions\Articles;

use App\Models\ArticleLike;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class AddLikeStatusToArticlesAction
{
    /**
     * Esegue la logica per aggiungere lo stato 'has_liked' a una collezione di articoli.
     *
     * @param Paginator|Collection|LengthAwarePaginator $articles La collezione di articoli (può essere paginata o standard).
     * @return Paginator|Collection|LengthAwarePaginator La stessa collezione di articoli, con l'attributo 'has_liked' aggiunto a ciascun modello.
     */
    public function execute(Paginator|Collection|LengthAwarePaginator $articles): Paginator|Collection|LengthAwarePaginator
    {
        // Se la collezione di articoli è vuota, non c'è nulla da fare.
        if ($articles->isEmpty()) {
            return $articles;
        }

        $likedArticleIds = [];

        // Controlliamo se un utente è autenticato.
        if (Auth::check()) {
            $userId = Auth::id();
            // Eseguiamo un'unica query per ottenere tutti gli ID degli articoli a cui l'utente ha messo "Mi Piace",
            // limitando la ricerca solo agli articoli presenti nella collezione passata.
            $likedArticleIds = ArticleLike::where('user_id', $userId)
                ->whereIn('article_id', $articles->pluck('id'))
                ->pluck('article_id')
                ->all();
        }

        // Iteriamo su ogni articolo della collezione per aggiungere l'attributo 'has_liked'.
        // Questo ciclo è più efficiente del precedente perché la query al DB è già stata fatta una sola volta.
        foreach ($articles as $article) {
            $article->has_liked = in_array($article->id, $likedArticleIds);
        }

        // Restituiamo la collezione di articoli modificata.
        return $articles;
    }
}
