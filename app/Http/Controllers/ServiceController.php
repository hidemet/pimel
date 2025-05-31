<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TargetCategory; // Importa il modello TargetCategory
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        // Recupera tutte le categorie di target che hanno almeno un servizio attivo associato,
        // oppure tutte le categorie se vuoi mostrarle anche se vuote.
        // Ordiniamo per nome o per un campo 'order' se lo aggiungessi in futuro.
        $targetCategories = TargetCategory::with([
            'services' => function ($query) {
                $query->where('is_active', true)->orderBy('name'); // Carica solo servizi attivi, ordinati per nome
            }
        ])
        ->orderBy('name') // Ordina le categorie per nome (es. Altri Servizi, Genitori, Professionisti...)
        ->get();

        // Opzionale: Se vuoi una categoria "Servizi non categorizzati" per i servizi con target_category_id = null
        $uncategorizedServices = Service::where('is_active', true)
                                        ->whereNull('target_category_id')
                                        ->orderBy('name')
                                        ->get();

        // Recupera tutti i servizi attivi per passarli alla modal (se necessario per la logica precedente)
        // Questa parte potrebbe non essere più necessaria se la modal carica i dati dinamicamente
        // o se i dati sono già inclusi in $targetCategories.
        $allActiveServices = Service::where('is_active', true)->orderBy('name')->get();


        return view('servizi.index', [
            'targetCategories' => $targetCategories,
            'uncategorizedServices' => $uncategorizedServices, // Passa i servizi non categorizzati
            'allActiveServices' => $allActiveServices // Mantenuto per ora, valuta se serve ancora
        ]);
    }
}