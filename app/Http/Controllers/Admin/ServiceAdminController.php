<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use App\Models\TargetCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceAdminController extends Controller {
    public function index(Request $request): View {
        $query = Service::query()->with('targetCategory');
        $query->orderBy('name', 'asc');
        $services = $query->paginate(15);
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi'],
        ];

        return view('admin.services.index', compact('services', 'breadcrumbs'));
    }

    public function create(): View {
        $service = new Service();
        $targetCategories = TargetCategory::orderBy('name')->get();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi', 'url' => route('admin.services.index')],
            ['label' => 'Nuovo Servizio'],
        ];

        return view('admin.services.create', compact('service', 'targetCategories', 'breadcrumbs'));
    }

    public function store(StoreServiceRequest $request): RedirectResponse {
        Service::create($request->validated());

        return redirect()->route('admin.services.index')
            ->with('success', 'Servizio creato con successo!');
    }

    public function edit(Service $service): View {
        $targetCategories = TargetCategory::orderBy('name')->get();
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Gestione Servizi', 'url' => route('admin.services.index')],
            ['label' => 'Modifica: ' . Str::limit($service->name, 30)],
        ];

        return view('admin.services.edit', compact('service', 'targetCategories', 'breadcrumbs'));
    }

    public function update(StoreServiceRequest $request, Service $service): RedirectResponse {
        $service->update($request->validated());

        return redirect()->route('admin.services.index')
            ->with('success', 'Servizio aggiornato con successo!');
    }

    public function destroy(Service $service): RedirectResponse {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Servizio eliminato con successo.');
    }
}
