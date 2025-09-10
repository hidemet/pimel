<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ArticleObserver;
use App\Models\Article;
use Illuminate\Pagination\Paginator; // Aggiungi questa riga


class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Article::observe(ArticleObserver::class);
        Paginator::useBootstrapFive(); // Usa useBootstrapFive() per Bootstrap 5
    }
}
