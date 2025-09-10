<?php

namespace App\Http\Controllers;

use App\Actions\Articles\AddLikeStatusToArticlesAction;
use App\Models\Article;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleLike;

class HomeController extends Controller
{
    /**
     * Mostra la homepage.
     */
    public function __invoke(AddLikeStatusToArticlesAction $addLikeStatusToArticlesAction): View
    {
        $query = Article::with(['rubric', 'author'])
            ->withCount([
                'likes',
                'comments' => function ($query) {
                    $query->where('is_approved', true);
                },
            ])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3);

        $latestArticles = $query->get();

        $latestArticles = $addLikeStatusToArticlesAction->execute($latestArticles);

        return view('home', [
            'latestArticles' => $latestArticles,
        ]);
    }
}
