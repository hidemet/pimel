<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User; // Assicurati che sia importato
use App\Models\ArticleLike; // Importa il modello ArticleLike
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa Auth
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importa Carbon per la gestione delle date

// AuthorizationException non è più strettamente necessaria qui se ci affidiamo solo alla policy
// e Laravel gestisce il 403, o se decidiamo di non fare il try-catch per il 404.

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Conteggi per i segmented buttons
        $statusCounts = Article::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $totalArticles = Article::count();

        // Filtri e ordinamento dalla request
        $currentStatusFilter = $request->input('status', 'all');
        $searchTerm = $request->input('search');
        $selectedRubricId = $request->input('rubric_id');
        $selectedAuthorId = $request->input('author_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sortBy = $request->input('sort_by', 'published_at'); // Default a published_at
        $sortDirection = $request->input('sort_direction', 'desc'); // Default a desc

        // Costruzione Query Base
        $query = Article::query()
                        ->with(['author', 'rubrics']) // Author serve per il filtro e la visualizzazione
                        ->withCount(['comments', 'likes']);

        // Applica Filtro Testuale
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('author', function ($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Applica Filtro Stato (dai segmented buttons)
        if ($currentStatusFilter !== 'all') {
            $query->where('status', $currentStatusFilter);
        }

        // Applica Filtro Rubrica (dal dropdown)
        if ($selectedRubricId && $selectedRubricId !== 'all') {
            $query->whereHas('rubrics', function ($q) use ($selectedRubricId) {
                $q->where('rubrics.id', $selectedRubricId);
            });
        }

        // Applica Filtro Autore (dal dropdown)
        if ($selectedAuthorId && $selectedAuthorId !== 'all') {
            $query->where('user_id', $selectedAuthorId);
        }

        // Applica Filtro Intervallo Date (dal dropdown)
        if ($dateFrom) {
            try { $query->whereDate('published_at', '>=', Carbon::parse($dateFrom)->startOfDay()); }
            catch (\Exception $e) { /* ignora data non valida */ }
        }
        if ($dateTo) {
            try { $query->whereDate('published_at', '<=', Carbon::parse($dateTo)->endOfDay()); }
            catch (\Exception $e) { /* ignora data non valida */ }
        }

        // Applica Ordinamento
        $validSorts = ['published_at', 'created_at', 'title', 'likes_count', 'comments_count'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('published_at', 'desc'); // Fallback default
        }
        // Se l'ordinamento primario è per titolo, aggiungi un secondario per data per coerenza
        if ($sortBy === 'title') {
            $query->orderBy('published_at', 'desc');
        }


        $articles = $query->paginate(15)->withQueryString();

        // Dati per la vista
        $articleDisplayStatuses = [
            'all' => ['text' => 'Tutti', 'icon' => 'inventory_2', 'count' => $totalArticles],
            'published' => ['text' => 'Pubblicati', 'icon' => 'public', 'count' => $statusCounts->get('published', 0)],
            'draft' => ['text' => 'Bozze', 'icon' => 'edit_document', 'count' => $statusCounts->get('draft', 0)],
            'scheduled' => ['text' => 'Pianificati', 'icon' => 'schedule', 'count' => $statusCounts->get('scheduled', 0)],
            'archived' => ['text' => 'Archiviati', 'icon' => 'archive', 'count' => $statusCounts->get('archived', 0)],
        ];
        $allRubrics = Rubric::orderBy('name')->get();
        $allAuthors = User::whereHas('articles')->orderBy('name')->get(); // Solo autori con articoli, o tutti gli admin: User::where('role','admin')->get();

        // Conteggio filtri attivi per il badge sul pulsante "Filtri"
        $activeFilterCount = 0;
        if ($searchTerm) $activeFilterCount++;
        if ($selectedRubricId && $selectedRubricId !== 'all') $activeFilterCount++;
        if ($selectedAuthorId && $selectedAuthorId !== 'all') $activeFilterCount++;
        if ($dateFrom || $dateTo) $activeFilterCount++;
        // Non contiamo lo stato principale (segmented button) qui,
        // né l'ordinamento come "filtro" per il badge.

        // Opzioni di ordinamento per il dropdown
        $sortOptions = [
            'published_at_desc' => 'Prima i più recenti (Pubblic.)',
            'published_at_asc' => 'Prima i meno recenti (Pubblic.)',
            'created_at_desc' => 'Prima i più recenti (Creaz.)',
            'created_at_asc' => 'Prima i meno recenti (Creaz.)',
            'title_asc' => 'Titolo (A-Z)',
            'title_desc' => 'Titolo (Z-A)',
            'likes_count_desc' => 'Più piaciuti',
            'comments_count_desc' => 'Più commentati',
        ];
        $currentSortKey = $sortBy . '_' . $sortDirection;


        return view('admin.articles.index', compact(
            'articles',
            'articleDisplayStatuses',
            'currentStatusFilter',
            'allRubrics',
            'allAuthors', // Passa gli autori
            'activeFilterCount', // Passa il conteggio dei filtri attivi
            'sortOptions',       // Passa le opzioni di ordinamento
            'currentSortKey'     // Passa l'ordinamento corrente
        ));
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Article $article): View // Route Model Binding
    {
        // L'autorizzazione è il primo passo. Laravel restituirà 403 se fallisce.
        // Se vuoi un 404 invece, puoi usare il try-catch:
        // try {
        //     $this->authorize('view', $article);
        // } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
        //     abort(404);
        // }
        $this->authorize('view', $article); // Lasciamo che Laravel gestisca il 403

        // Eager load delle relazioni
        $article->load([
            'author',
            'rubrics',
            'comments' => function ($query) {
                $query->where('is_approved', true)
                    ->whereNull('parent_id')
                    ->with(['user', 'replies' => function ($replyQuery) {
                        $replyQuery->where('is_approved', true)
                            ->with('user')
                            ->orderBy('created_at', 'asc');
                    }])
                    ->orderBy('created_at', 'desc');
            },
            'likes', // Carichiamo comunque i likes per il conteggio, se non usi withCount qui
        ]);

        // Imposta la proprietà 'has_liked' per il singolo articolo visualizzato
        if (Auth::check()) {
            $article->has_liked = $article->likes()->where('user_id', Auth::id())->exists();
            // Qui l'accessor `liked_by_current_user` sarebbe stato equivalente e forse più pulito
            // $article->has_liked = $article->liked_by_current_user;
            // Ma per coerenza con il metodo index, usiamo 'has_liked'.
        } else {
            $article->has_liked = false;
        }
        // Conteggio likes (se non usi withCount specifico qui, puoi farlo dal load precedente)
        $article->likes_count = $article->likes->count();


        // Recupera articoli correlati
        $relatedArticlesQuery = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->whereHas('rubrics', function ($query) use ($article) {
                $query->whereIn('rubrics.id', $article->rubrics->pluck('id'));
            })
            ->with(['rubrics'])
            ->withCount(['likes', 'comments' => fn($q) => $q->where('is_approved', true)])
            ->orderBy('published_at', 'desc')
            ->take(3);

        $relatedArticles = $relatedArticlesQuery->get();

        // Aggiungi 'has_liked' anche agli articoli correlati
        if (Auth::check()) {
            $userId = Auth::id();
            $likedRelatedArticleIds = ArticleLike::where('user_id', $userId)
                                              ->whereIn('article_id', $relatedArticles->pluck('id')->all())
                                              ->pluck('article_id')
                                              ->all();
            foreach ($relatedArticles as $relatedArticle) {
                $relatedArticle->has_liked = in_array($relatedArticle->id, $likedRelatedArticleIds);
            }
        } else {
            foreach ($relatedArticles as $relatedArticle) {
                $relatedArticle->has_liked = false;
            }
        }


        return view('blog.show', [
            'article'         => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    // I metodi create, store, edit, update, destroy verranno implementati
    // per l'area amministrativa.
}