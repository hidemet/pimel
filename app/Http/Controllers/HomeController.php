<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Rubric;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class HomeController extends Controller
{
    
    public function __invoke(): View {
        $latestArticles = Article::with(['rubric', 'author'])
            ->withCount([
                'likes',
                'comments' => function ($query) {
                    $query->where('is_approved', true);
                },
            ])
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Logica per aggiungere lo stato 'has_liked' (precedentemente in Action)
        if ($latestArticles->isNotEmpty() && Auth::check()) {
            $likedArticleIds = ArticleLike::where('user_id', Auth::id())
                ->whereIn('article_id', $latestArticles->pluck('id'))
                ->pluck('article_id')
                ->flip();

            $latestArticles->each(function ($article) use ($likedArticleIds) {
                $article->has_liked = $likedArticleIds->has($article->id);
            });
        } else {
            $latestArticles->each(fn($article) => $article->has_liked = false);
        }

        return view('home', [
            'latestArticles' => $latestArticles,
        ]);
    }
}

