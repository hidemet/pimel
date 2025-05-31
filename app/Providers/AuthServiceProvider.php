<?php

namespace App\Providers;

// Importa i modelli e le policy che vuoi registrare
use App\Models\Article;
use App\Policies\ArticlePolicy;
// Se avrai altre policy, aggiungi i loro `use` qui

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; // Descommenta se userai anche i Gates direttamente

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Esempio commentato
        Article::class => ArticlePolicy::class,
        // Aggiungi qui altre mappature modello => policy quando le crei
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Qui puoi definire i tuoi Gates, se necessario
        // Esempio:
        // Gate::define('manage-settings', function ($user) {
        //     return $user->isAdmin();
        // });
    }
}