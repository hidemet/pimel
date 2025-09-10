<?php

namespace App\Providers;

// Importa i modelli e le policy che vuoi registrare
use App\Models\Article;
use App\Policies\ArticlePolicy;
use App\Models\Rubric; // Importa il modello Rubric
use App\Policies\RubricPolicy; // Importa la policy RubricPolicy

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
        Article::class => ArticlePolicy::class,
        Rubric::class => RubricPolicy::class, 
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