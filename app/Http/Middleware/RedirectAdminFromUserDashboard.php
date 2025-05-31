<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminFromUserDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se l'utente è autenticato ed è un admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            // Reindirizzalo alla dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // Altrimenti, procedi con la richiesta originale (mostra la dashboard utente)
        return $next($request);
    }
}