<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Articles\SaveArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;


class ArticleAdminController extends Controller
{
    public function index(Request $request): View
    {

        $viewData = $this->prepareViewData($request);
        $query = Article::query()
            ->with(['author', 'rubric']) // Carica le relazioni 'author' e 'rubric'
            ->withCount(['comments', 'likes']); // conta i like e i commenti

        // Applica i filtri e l'ordinamento alla query
        $this->applyFiltersAndSorting($query, $request, $viewData['currentStatusFilter']);
        // Esegue la query, pagina i risultati e mantiene i parametri URL
        $articles = $query->paginate(15)->withQueryString(); // Assicura che i link della paginazione mantengano i filtri e l'ordinamento attuali

        // Unisce i dati per la view e i risultati della query e reindirizza alla view
        return view('admin.articles.index', array_merge($viewData, compact('articles')));
    }

    private function prepareViewData(Request $request): array
    {
        $statusCounts = Article::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // legge lo stato corrente dall'URL (?status=...) con published come default
        $currentStatusFilter = $request->input('status', 'Pubblicato');

        $articleDisplayStatuses = [
            'Pubblicato' => ['text' => 'Pubblicati',  'icon' => 'public',        'count' => $statusCounts->get('Pubblicato', 0)],
            'Bozza'      => ['text' => 'Bozze',       'icon' => 'edit_document', 'count' => $statusCounts->get('Bozza', 0)],
            'Archiviato' => ['text' => 'Archiviati',  'icon' => 'archive',       'count' => $statusCounts->get('Archiviato', 0)],
        ];

        $activeFilterCount = collect($request->input('search'))->filter()->count();

        $currentSortDirection = $request->input('sort_direction', 'desc');

        $breadcrumbs = [['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Gestione Articoli']];

        return compact('articleDisplayStatuses', 'currentStatusFilter', 'activeFilterCount', 'currentSortDirection', 'breadcrumbs');
    }

    private function applyFiltersAndSorting(Builder $query, Request $request, string $currentStatusFilter): void
    {
        // Filtra sempre per lo stato corrente (es. 'published'))
        $query->where('status', $currentStatusFilter);

        // Manteniamo solo la ricerca per titolo e autore

        // se c'è un termine di ricerca, applica il filtro
        if ($searchTerm = $request->input('search')) {
            $query->where(function (Builder $q) use ($searchTerm) {
                // Cerca nel titolo dell'articolo o nel nome dell'autore
                $q->where('articles.title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function (Builder $subQ) use ($searchTerm) {
                        $subQ->where('users.name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Ordiniamo solo per data di pubblicazione
        $sortDirection = $request->input('sort_direction', 'desc');

        // Se lo stato è 'bozza', 'published_at' può essere null, quindi ordiniamo per data di creazione
        $orderByColumn = $currentStatusFilter === 'Bozza' ? 'created_at' : 'published_at';

        $query->orderBy($orderByColumn, $sortDirection === 'asc' ? 'asc' : 'desc');
    }

    public function create(): View
    {
        $article = new Article(['user_id' => Auth::id()]);
        $rubrics = Rubric::orderBy('name')->get();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Articoli', 'url' => route('admin.articles.index')],
            ['label' => 'Nuovo Articolo']
        ];
        return view('admin.articles.create', compact('article', 'rubrics', 'breadcrumbs'));
    }

    public function show(Article $article): RedirectResponse
    {
        return redirect()->route('admin.articles.edit', $article);
    }

    public function edit(Article $article): View
    {
        $article->load('rubric');

        $rubrics = Rubric::orderBy('name')->get();

        // Recupera gli utenti che possono essere autori (es. tutti gli admin)
        $authors = User::where('is_admin', true)->orderBy('name')->get();


        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Articoli', 'url' => route('admin.articles.index')],
            ['label' => 'Modifica: ' . Str::limit($article->title, 30)]
        ];




        return view('admin.articles.edit', [
            'article' => $article,
            'rubrics' => $rubrics,
            'authors' => $authors,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request, SaveArticleAction $saveArticleAction): RedirectResponse
    {
        $article = new Article();
        $saveArticleAction->execute($article, $request->validated(), $request);
        return redirect()->route('admin.articles.index')->with('success', 'Articolo creato con successo!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArticleRequest $request, Article $article, SaveArticleAction $saveArticleAction): RedirectResponse
    {
        $saveArticleAction->execute($article, $request->validated(), $request);
        return redirect()->route('admin.articles.index')->with('success', 'Articolo aggiornato con successo!');
    }

    public function updateStatus(Request $request, Article $article, string $newStatus): RedirectResponse
    {
        $this->authorize('update', $article);
        if (!in_array($newStatus, ['Pubblicato', 'Bozza', 'Archiviato'])) {
            return redirect()->back()->with('error', 'Stato non valido.');
        }
        $article->status = $newStatus;
        if ($newStatus === 'Pubblicato' && !$article->published_at) {
            $article->published_at = now();
        } elseif (in_array($newStatus, ['Bozza', 'Archiviato'])) {
            $article->published_at = null;
        }
        $article->save();
        return redirect()->route('admin.articles.index', ['status' => $newStatus])
            ->with('success', "Stato dell'articolo «{$article->title}» aggiornato a \"{$newStatus}\".");
    }


    public function destroy(Request $request, Article $article): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $article);

        try {
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }
            $article->delete();

            // Controlla se la richiesta è AJAX
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Articolo eliminato con successo.'
                ]);
            }

            return redirect()->route('admin.articles.index')->with('success', 'Articolo eliminato con successo.');
        } catch (\Exception $e) {
            \Log::error("Errore eliminazione articolo ID {$article->id}: " . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Si è verificato un errore durante l\'eliminazione dell\'articolo.'
                ], 500); // Internal Server Error
            }

            return redirect()->back()->with('error', 'Impossibile eliminare l\'articolo.');
        }
    }
}
