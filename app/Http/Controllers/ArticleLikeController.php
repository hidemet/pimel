<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleLikeController extends Controller {
    public function toggleLike(Request $request, Article $article): JsonResponse {
        $user = Auth::user();

        $like = ArticleLike::where([
            'user_id' => $user->id,
            'article_id' => $article->id
        ])->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ArticleLike::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $article->likes()->count(),
        ]);
    }
}
