<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TargetCategory;
use Illuminate\View\View;

class ServiceController extends Controller {
    public function index(): View {
        $targetCategories = TargetCategory::with([
            'services' => fn($query) => $query->orderBy('name')
        ])->orderBy('name')->get();

        $uncategorizedServices = Service::whereNull('target_category_id')
            ->orderBy('name')
            ->get();

        $allActiveServices = Service::orderBy('name')->get();

        return view('servizi.index', [
            'targetCategories' => $targetCategories,
            'uncategorizedServices' => $uncategorizedServices,
            'allActiveServices' => $allActiveServices,
        ]);
    }
}
