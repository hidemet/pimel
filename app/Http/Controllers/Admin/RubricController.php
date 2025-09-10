<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRubricRequest; // <-- ASSICURATI CHE L'IMPORT SIA QUESTO
use App\Models\Rubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RubricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Rubric::class);

        // 1. Filtri e ordinamento dalla request
        $searchTerm = $request->input('search');
        // L'ordinamento ora può essere solo 'asc' o 'desc' per il nome
        $sortDirection = $request->input('sort_direction', 'asc');

        // 2. Costruzione Query Base
        $query = Rubric::query()
            ->withCount('articles'); // Manteniamo il conteggio per visualizzarlo

        // Applica Filtro Testuale
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Applica Ordinamento Semplificato (solo per nome)
        $query->orderBy('name', $sortDirection === 'desc' ? 'desc' : 'asc');

        // 3. Paginazione
        $rubrics = $query->paginate(15)->withQueryString();

        // 4. Dati per l'interfaccia (ora molto più semplici)
        $currentSort = $sortDirection;

        // 5. Breadcrumb
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche']
        ];

        // 6. Ritorna la vista
        return view('admin.rubrics.index', compact(
            'rubrics',
            'searchTerm',
            'currentSort', // Passiamo solo la direzione corrente
            'breadcrumbs'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rubric = new Rubric();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche', 'url' => route('admin.rubrics.index')],
            ['label' => 'Nuova Rubrica']
        ];
        return view('admin.rubrics.create', compact('rubric', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     * --- METODO CORRETTO ---
     */
    public function store(StoreRubricRequest $request): RedirectResponse
    {
        Rubric::create($request->validated());

        return redirect()->route('admin.rubrics.index')
            ->with('success', 'Rubrica creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubric $rubric): RedirectResponse
    {
        return redirect()->route('admin.rubrics.edit', $rubric);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubric $rubric): View
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Rubriche', 'url' => route('admin.rubrics.index')],
            ['label' => 'Modifica: ' . Str::limit($rubric->name, 30)]
        ];
        return view('admin.rubrics.edit', compact('rubric', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     * --- METODO CORRETTO ---
     */
    public function update(StoreRubricRequest $request, Rubric $rubric): RedirectResponse
    {
        $rubric->update($request->validated());

        return redirect()->route('admin.rubrics.index')
            ->with('success', 'Rubrica aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubric $rubric): RedirectResponse
    {
        // Questa logica è corretta, la lasciamo invariata
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
