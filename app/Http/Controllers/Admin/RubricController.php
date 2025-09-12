<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRubricRequest;
use App\Models\Rubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RubricController extends Controller {
    public function index(Request $request): View {
        $rubrics = Rubric::query()
            ->withCount('articles')
            ->orderBy('name', 'asc')
            ->paginate(15);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche'],
        ];

        return view('admin.rubrics.index', compact(
            'rubrics',
            'breadcrumbs'
        ));
    }

    public function create(): View {
        $rubric = new Rubric();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche', 'url' => route('admin.rubrics.index')],
            ['label' => 'Nuova Rubrica'],
        ];

        return view('admin.rubrics.create', compact('rubric', 'breadcrumbs'));
    }

    public function store(StoreRubricRequest $request): RedirectResponse {
        Rubric::create($request->validated());

        return redirect()->route('admin.rubrics.index')
            ->with('success', 'Rubrica creata con successo!');
    }

    public function edit(Rubric $rubric): View {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche', 'url' => route('admin.rubrics.index')],
            ['label' => 'Modifica: ' . Str::limit($rubric->name, 30)],
        ];

        return view('admin.rubrics.edit', compact('rubric', 'breadcrumbs'));
    }

    public function update(StoreRubricRequest $request, Rubric $rubric): RedirectResponse {
        $rubric->update($request->validated());

        return redirect()->route('admin.rubrics.index')
            ->with('success', 'Rubrica aggiornata con successo!');
    }

    public function destroy(Rubric $rubric): RedirectResponse {
        try {
            $rubric->delete();

            return redirect()->route('admin.rubrics.index')
                ->with('success', 'Rubrica eliminata con successo.');
        } catch (\Exception $e) {
            \Log::error("Errore durante l'eliminazione della rubrica ID {$rubric->id}: " . $e->getMessage());

            return redirect()->route('admin.rubrics.index')
                ->with('error', 'Impossibile eliminare la rubrica. Potrebbero esserci articoli associati.');
        }
    }
}
