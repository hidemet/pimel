<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Actions\Articles\AddLikeStatusToArticlesAction;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AddLikeStatusToArticlesAction $addLikeStatusToArticlesAction): View|JsonResponse
    {
        $selectedRubricSlug = $request->query('rubrica');
        $selectedRubric = null;
        $sortByInput = $request->query('ordina_per', 'published_at_desc');

        $query = Article::query()
            ->where('status', 'Pubblicato')
            ->where('published_at', '<=', now())
            ->with(['author', 'rubric'])
            ->withCount(['likes', 'comments' => fn($q) => $q->where('is_approved', true)]);

        // Applica Filtro Rubrica se presente
        if ($selectedRubricSlug) {
            $selectedRubric = Rubric::where('slug', $selectedRubricSlug)->first();
            if ($selectedRubric) {
                $query->where('rubric_id', $selectedRubric->id);
            }
        }

        // Applica Ordinamento
        switch ($sortByInput) {
            case 'likes_desc':
                $query->orderBy('likes_count', 'desc')->orderBy('published_at', 'desc');
                break;
            case 'comments_desc':
                $query->orderBy('comments_count', 'desc')->orderBy('published_at', 'desc');
                break;
            case 'published_at_desc':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $articles = $query->paginate(9)->withQueryString();

        $articles = $addLikeStatusToArticlesAction->execute($articles);


        if ($request->wantsJson()) {
            // Renderizza la vista parziale con la nuova lista di articoli
            $articlesHtml = view('components.shared._article-list-content', compact('articles'))->render();
            // Renderizza la nuova paginazione
            $paginationHtml = $articles->hasPages() ? $articles->links()->toHtml() : '';

            return response()->json([
                'articles_html' => $articlesHtml,
                'pagination_html' => $paginationHtml,
            ]);
        }
        // === FINE LOGICA AJAX ===

        // La logica per le richieste standard rimane invariata
        $rubrics = Rubric::orderBy('name')->get();

        return view('blog.index', compact(
            'articles',
            'rubrics',
            'selectedRubricSlug',
            'selectedRubric',
            'sortByInput'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): View
    {
        $this->authorize('view', $article);

        $article->load([
            'author',
            'rubric',
            'comments' => function ($query) {
                $query->where('is_approved', true)
                    ->with(['user'])
                    ->orderBy('created_at', 'desc');
            }
        ])->loadCount([
            'likes',
            'comments as approved_comments_count' => function ($query) {
                $query->where('is_approved', true);
            }
        ]);

        // logica per decidere se mostrare il police pieno o vuoto
        if (Auth::check()) {
            // creo la proprietà booleana has_liked per l'articolo corrente
            $article->has_liked = $article->likes()->where('user_id', Auth::id())->exists();
        } else {
            $article->has_liked = false;
        }

        // conto i likes
        $article->likes_count = $article->likes->count();

        // Commenti approvati (root) già caricati nella relazione $article->comments
        $comments = $article->comments; // solo quelli approvati root con replies approvate

        // Commenti dell'utente autenticato in attesa di approvazione
        $pendingComments = collect();
        if (Auth::check()) {
            $pendingComments = $article->comments()
                ->where('user_id', Auth::id())
                ->where('is_approved', false)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Totale commenti visibili (approvati) alias già calcolato da loadCount
        $totalVisibleComments = $article->approved_comments_count ?? $comments->count();

        return view('blog.show', [
            'article'               => $article,
            'comments'              => $comments,
            'pendingComments'       => $pendingComments,
            'totalVisibleComments'  => $totalVisibleComments,
        ]);
    }
}
