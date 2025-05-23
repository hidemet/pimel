<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Service;

class PageController extends Controller {
    /**
     * Mostra la pagina "Chi Sono".
     */
    public function about(): View {

                                 // In futuro potresti recuperare contenuto dinamico per questa pagina dal DB
        return view( 'chi-sono' ); // Assumendo 'chi-sono.blade.php'
    }

    /**
     * Mostra il form di contatto.
     */
    public function contactForm(): View {
        // Recupera i servizi per il dropdown nel form di contatto (opzionale)
        $services = \App\Models\Service::where( 'is_active', true )
            ->orderBy( 'name' )->pluck( 'name', 'name' );

        // pluck('name', 'name') crea un array ['Nome Servizio' => 'Nome Servizio'] adatto per il select.

        // Se vuoi passare lo slug o l'id, usa pluck('name', 'slug') o pluck('name', 'id')

        return view( 'contatti.form', compact( 'services' ) );
        // Assumendo 'contatti.form.blade.php'
    }

    /**
     * Mostra il form per la newsletter.
     */
    public function newsletterForm(): View {
        $rubrics = \App\Models\Rubric::orderBy( 'name' )->get();
        return view( 'newsletter.form', compact( 'rubrics' ) );
        // Assumendo 'newsletter.form.blade.php'
    }
}