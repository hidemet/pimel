<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Articles\SaveArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Rubric;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleAdminController extends Controller {
    public function index(Request $request): View {
        $articles = Article::query()
            ->with(['author', 'rubric'])
            ->withCount(['comments', 'likes'])
            ->orderByDate('desc')
            ->paginate(15);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Articoli'],
        ];

        return view('admin.articles.index', compact(
            'articles',
            'breadcrumbs'
        ));
    }

    public function create(): View {
        $article = new Article(['user_id' => Auth::id()]);
        $rubrics = Rubric::orderBy('name')->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Articoli', 'url' => route('admin.articles.index')],
            ['label' => 'Nuovo Articolo'],
        ];

        return view('admin.articles.create', compact('article', 'rubrics', 'breadcrumbs'));
    }

    public function edit(Article $article): View {
        $article->load('rubric');
        $rubrics = Rubric::orderBy('name')->get();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Articoli', 'url' => route('admin.articles.index')],
            ['label' => 'Modifica: ' . Str::limit($article->title, 30)],
        ];

        return view(
            'admin.articles.edit',
            compact('article', 'rubrics', 'breadcrumbs')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse {
        $article = new Article();

        $article->user_id = Auth::id();
        $article->fill($request->validated());

        $this->handleImageUpload($article, $request);

        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Articolo creato con successo!');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArticleRequest $request, Article $article): RedirectResponse {
        $article->fill($request->validated());
        $this->handleImageUpload($article, $request);
        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Articolo aggiornato con successo!');
    }

    public function destroy(Request $request, Article $article): JsonResponse {
        try {
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Articolo eliminato con successo.',
            ]);
        } catch (\Exception $e) {
            \Log::error("Errore eliminazione articolo ID {$article->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Si è verificato un errore durante l\'eliminazione dell\'articolo.',
            ], 500); // Internal Server Error
        }
    }

    private function handleImageUpload(Article $article, Request $request): void {
        if ($request->boolean('remove_image')) {
            if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
                Storage::disk('public')->delete($article->image_path);
            }
            $article->image_path = null;
        } elseif ($request->hasFile('image_path')) {
            if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
                Storage::disk('public')->delete($article->image_path);
            }
            $path = $request->file('image_path')->store('article_images', 'public');
            $article->image_path = $path;
        }
    }
}
