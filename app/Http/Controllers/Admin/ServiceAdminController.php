<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest; // <-- IMPORTA LA FORM REQUEST
use App\Models\Service;
use App\Models\TargetCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
// Rimuovi 'Rule' perché non è più usato qui
// use Illuminate\Validation\Rule;

class ServiceAdminController extends Controller
{
    // ... (index, create, edit rimangono invariati) ...
    public function index(Request $request): View
    {
        $query = Service::query()->with('targetCategory');
        $query->orderBy('name', 'asc');
        $services = $query->paginate(15);
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi']
        ];
        return view('admin.services.index', compact('services', 'breadcrumbs'));
    }

    public function create(): View
    {
        $service = new Service(['is_active' => true]);
        $targetCategories = TargetCategory::orderBy('name')->get();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi', 'url' => route('admin.services.index')],
            ['label' => 'Nuovo Servizio']
        ];
        return view('admin.services.create', compact('service', 'targetCategories', 'breadcrumbs'));
    }


    public function store(StoreServiceRequest $request): RedirectResponse
    {
        // Validazione e autorizzazione sono gestite dalla Form Request.
        Service::create($request->validated());

        return redirect()->route('admin.services.index')
                         ->with('success', 'Servizio creato con successo!');
    }
    
    public function edit(Service $service): View
    {
        $targetCategories = TargetCategory::orderBy('name')->get();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi', 'url' => route('admin.services.index')],
            ['label' => 'Modifica: ' . Str::limit($service->name, 30)]
        ];
        return view('admin.services.edit', compact('service', 'targetCategories', 'breadcrumbs'));
    }

    /**
     * Aggiorna un servizio esistente nel database.
     * --- METODO RIFATTORIZZATO ---
     */
    public function update(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        // Validazione e autorizzazione sono gestite dalla Form Request.
        // La preparazione dei dati (es. 'is_active') avviene nel metodo prepareForValidation della request.
        $service->update($request->validated());

        return redirect()->route('admin.services.index')
                         ->with('success', 'Servizio aggiornato con successo!');
    }

    // ... (destroy rimane invariato) ...
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return redirect()->route('admin.services.index')
                         ->with('success', 'Servizio eliminato con successo.');
    }

    // --- RIMUOVI QUESTO METODO PRIVATO DAL CONTROLLER ---
    // private function validationRules(int $serviceId = null): array { ... }
}