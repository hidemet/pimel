<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleLikeController extends Controller
{
    public function toggleLike(Request $request, Article $article)
    {
        $user = Auth::user();

        // Cerca se esiste già un like da questo utente per questo articolo
        $like = ArticleLike::where('user_id', $user->id)
                           ->where('article_id', $article->id)
                           ->first();

        $liked = false;

        if ($like) {
            // Se il like esiste, lo rimuoviamo (unlike)
            $like->delete();
            $liked = false;
        } else {
            // Se il like non esiste, lo creiamo (like)
            ArticleLike::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $liked = true;
        }

        // Ritorna il nuovo stato del like e il conteggio aggiornato dei like
        return response()->json([
            'liked' => $liked,
            'likes_count' => $article->likes()->count() // Ricalcola il conteggio dei like
        ]);
    }
}