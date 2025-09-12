<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Rubric;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleController extends Controller {
    public function index(Request $request): View|JsonResponse {
        $selectedRubricSlug = $request->query('rubrica');
        $sortByInput = $request->query('ordina_per', 'published_at_desc');

        $query = Article::query()
            ->published()
            ->with(['author', 'rubric'])
            ->withCount(['likes', 'comments' => fn($q) => $q->where('is_approved', true)]);

        $selectedRubric = $this->applyRubricFilter($query, $selectedRubricSlug);
        $this->applySorting($query, $sortByInput);

        $articles = $query->paginate(9)->withQueryString();

        // Logica per aggiungere lo stato 'has_liked' (precedentemente in Action)
        if ($articles->isNotEmpty() && Auth::check()) {
            $likedArticleIds = ArticleLike::where('user_id', Auth::id())
                ->whereIn('article_id', $articles->pluck('id'))
                ->pluck('article_id')
                ->flip();

            $articles->each(function ($article) use ($likedArticleIds) {
                $article->has_liked = $likedArticleIds->has($article->id);
            });
        } else {
            $articles->each(fn($article) => $article->has_liked = false);
        }

        if ($request->wantsJson()) {
            return $this->handleAjaxRequest($articles);
        }

        $rubrics = Rubric::orderBy('name')->get();

        return view('blog.index', compact(
            'articles',
            'rubrics',
            'selectedRubricSlug',
            'selectedRubric',
            'sortByInput'
        ));
    }

    public function show(Article $article): View {

        $article->load([
            'author',
            'rubric',
            'comments' => fn($query) => $query->where('is_approved', true)
                ->with('user')
                ->orderBy('created_at', 'desc')
        ])->loadCount([
            'likes',
            'comments as approved_comments_count' => fn($query) => $query->where('is_approved', true)
        ]);

        $article->has_liked = Auth::check()
            ? $article->likes()->where('user_id', Auth::id())->exists()
            : false;

        $pendingComments = Auth::check()
            ? $article->comments()
            ->where('user_id', Auth::id())
            ->where('is_approved', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            : collect();

        return view('blog.show', [
            'article' => $article,
            'comments' => $article->comments,
            'pendingComments' => $pendingComments,
            'totalVisibleComments' => $article->approved_comments_count,
        ]);
    }

    private function applyRubricFilter($query, ?string $selectedRubricSlug): ?Rubric {
        if (!$selectedRubricSlug) {
            return null;
        }

        $selectedRubric = Rubric::where('slug', $selectedRubricSlug)->first();

        if ($selectedRubric) {
            $query->where('rubric_id', $selectedRubric->id);
        }

        return $selectedRubric;
    }

    private function applySorting($query, string $sortBy): void {
        match ($sortBy) {
            'likes_desc' => $query->orderBy('likes_count', 'desc')->orderBy('published_at', 'desc'),
            'comments_desc' => $query->orderBy('comments_count', 'desc')->orderBy('published_at', 'desc'),
            default => $query->orderBy('published_at', 'desc')
        };
    }

    private function handleAjaxRequest($articles): JsonResponse {
        $articlesHtml = view('components.shared._article-list-content', compact('articles'))->render();
        $paginationHtml = $articles->hasPages() ? $articles->links()->toHtml() : '';

        return response()->json([
            'articles_html' => $articlesHtml,
            'pagination_html' => $paginationHtml,
        ]);
    }
}
