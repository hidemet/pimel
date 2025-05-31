<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View as IlluminateView; // Rinomina per evitare conflitto con View::class
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str; // Assicurati sia importato

class AppLayout extends Component
{
    public string $bodyClass;
    public string $navBgClass;
    public string $pageHeaderBgClass;
    public string $contentClass;
    public bool $isAdminArea;
    public bool $useAdminNavigation; // NUOVA PROPRIETÀ

    public function __construct(
        string $bodyClass = 'bg-body-public-default',
        string $navBgClass = 'bg-body-public-default', // Questo sarà meno usato se abbiamo header separati
        string $pageHeaderBgClass = 'bg-body-public-default',
        string $contentClass = 'bg-transparent',
        bool $useAdminNavigation = false // Default a false
    ) {
        $this->bodyClass = $bodyClass;
        $this->navBgClass = $navBgClass; // Potrebbe non essere più necessario per l'header pubblico
        $this->pageHeaderBgClass = $pageHeaderBgClass;
        $this->contentClass = $contentClass;
        $this->isAdminArea = Route::currentRouteName() && Str::startsWith(Route::currentRouteName(), 'admin.');

        // Se siamo nell'area admin, usiamo sempre la navigazione admin,
        // altrimenti usiamo il valore passato (o il default false).
        $this->useAdminNavigation = $this->isAdminArea ?: $useAdminNavigation;

        // Se siamo nell'area admin, potremmo voler sovrascrivere alcune classi di default.
        if ($this->isAdminArea) {
            $this->bodyClass = $bodyClass === 'bg-body-public-default' ? 'bg-body-private-default' : $bodyClass;
            // $this->navBgClass = 'bg-dark navbar-dark'; // Questa logica ora è nel AdminHeaderComponent
        }
    }

    public function render(): IlluminateView // Usa l'alias per Illuminate\View\View
    {
        return view('layouts.app');
    }
}