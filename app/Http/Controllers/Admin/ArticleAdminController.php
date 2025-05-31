<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Assicurati che DB sia importato

class ArticleAdminController extends Controller
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
        $sortBy = $request->input('sort_by', 'published_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Costruzione Query Base
        $query = Article::query()
                        ->with(['author', 'rubrics'])
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
        $allAuthors = User::whereHas('articles')->orderBy('name')->get();

        // Conteggio filtri attivi per il badge sul pulsante "Filtri"
        $activeFilterCount = 0;
        if ($searchTerm) $activeFilterCount++; // Conteggio ricerca testuale
        if ($selectedRubricId && $selectedRubricId !== 'all') $activeFilterCount++;
        if ($selectedAuthorId && $selectedAuthorId !== 'all') $activeFilterCount++;
        if ($dateFrom || $dateTo) $activeFilterCount++;

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
            'allAuthors',
            'activeFilterCount', // <-- Variabile passata correttamente
            'sortOptions',
            'currentSortKey'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $article = new Article(['user_id' => Auth::id()]);
        $rubrics = Rubric::orderBy('name')->get();
        $authors = User::where('role', 'admin')->orderBy('name')->get();
        $articleStatuses = ['draft' => 'Bozza', 'published' => 'Pubblicato', 'scheduled' => 'Schedulato'];
        return view('admin.articles.create', compact('article', 'rubrics', 'authors', 'articleStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate($this->validationRules(), $this->validationMessages());
        $article = new Article();
        $this->saveArticleData($article, $validatedData, $request);
        return redirect()->route('admin.articles.index')->with('success', 'Articolo creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): RedirectResponse
    {
        return redirect()->route('admin.articles.edit', $article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article): View
    {
        $rubrics = Rubric::orderBy('name')->get();
        $authors = User::where('role', 'admin')->orderBy('name')->get();
        $articleStatuses = ['draft' => 'Bozza', 'published' => 'Pubblicato', 'scheduled' => 'Schedulato', 'archived' => 'Archiviato'];
        return view('admin.articles.edit', compact('article', 'rubrics', 'authors', 'articleStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $rules = $this->validationRules($article->id);
        $validatedData = $request->validate($rules, $this->validationMessages());
        $this->saveArticleData($article, $validatedData, $request);
        return redirect()->route('admin.articles.index')->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image_path) {
            Storage::disk('public')->delete($article->image_path);
        }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Articolo eliminato con successo.');
    }

    private function validationRules($articleId = null): array
    {
        $titleRule = 'required|string|max:255';
        if ($articleId) {
            $titleRule .= '|unique:articles,title,' . $articleId;
        } else {
            $titleRule .= '|unique:articles,title';
        }
        return [
            'title' => $titleRule,
            'body' => 'required|string',
            'rubric_ids' => 'nullable|array',
            'rubric_ids.*' => 'exists:rubrics,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at_date' => 'nullable|date_format:Y-m-d|required_if:status,scheduled|required_if:status,published',
            'published_at_time' => 'nullable|date_format:H:i',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'remove_image' => 'nullable|boolean',
            'excerpt' => 'nullable|string|max:1000',
            'reading_time' => 'nullable|integer|min:0',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.unique' => 'Esiste già un articolo con questo titolo.',
            'body.required' => 'Il corpo dell\'articolo è obbligatorio.',
            'user_id.required' => 'Devi selezionare un autore.',
            'status.required' => 'Lo stato dell\'articolo è obbligatorio.',
            'published_at_date.required_if' => 'La data di pubblicazione è obbligatoria se lo stato è "Pubblicato" o "Schedulato".',
            'image_path.image' => 'Il file caricato deve essere un\'immagine.',
            'image_path.mimes' => 'L\'immagine deve essere di tipo: jpeg, png, jpg, gif, svg, webp.',
            'image_path.max' => 'L\'immagine non può superare i 4MB.',
        ];
    }

    private function saveArticleData(Article $article, array $validatedData, Request $request): void
    {
        $article->title = $validatedData['title'];
        if ($article->isDirty('title') || !$article->exists) {
            $article->slug = Str::slug($validatedData['title']);
            $originalSlug = $article->slug;
            $counter = 1;
            while (Article::where('slug', $article->slug)->where('id', '!=', $article->id)->exists()) {
                $article->slug = $originalSlug . '-' . $counter++;
            }
        }
        $article->body = $validatedData['body'];
        $article->user_id = $validatedData['user_id'];
        $article->status = $validatedData['status'];
        if (!empty($validatedData['published_at_date'])) {
            $dateTimeString = $validatedData['published_at_date'];
            $time = !empty($validatedData['published_at_time']) ? $validatedData['published_at_time'] : '00:00';
            $article->published_at = Carbon::createFromFormat('Y-m-d H:i', $dateTimeString . ' ' . $time)->toDateTimeString();
        } else {
            $article->published_at = ($validatedData['status'] === 'published' && !$article->published_at) ? now() : null;
        }
        if (in_array($validatedData['status'], ['draft', 'archived'])) {
            $article->published_at = null;
        }
        if ($request->boolean('remove_image') && $article->image_path) {
            Storage::disk('public')->delete($article->image_path);
            $article->image_path = null;
        } elseif ($request->hasFile('image_path')) {
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }
            $path = $request->file('image_path')->store('article_images', 'public');
            $article->image_path = $path;
        }
        $article->excerpt = $validatedData['excerpt'] ?? Str::words(strip_tags($validatedData['body']), 30, '...');
        $article->reading_time = $validatedData['reading_time'];
        $article->meta_description = $validatedData['meta_description'];
        $article->meta_keywords = $validatedData['meta_keywords'];
        $article->save();
        if (isset($validatedData['rubric_ids'])) {
            $article->rubrics()->sync($validatedData['rubric_ids']);
        } else {
            $article->rubrics()->detach();
        }
    }

    
}