<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa Auth
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response {
        if ( Auth::check() && Auth::user()->isAdmin() ) {
            // Assumendo che il modello User abbia un metodo isAdmin() o un campo 'role'
            return $next( $request );
        }

        // Se non è admin, reindirizza o restituisci un errore
        // abort(403, 'Accesso non autorizzato.');
        return redirect( '/' )->with( 'error', 'Accesso non autorizzato.' );
        // Reindirizza alla home con un messaggio
    }
}