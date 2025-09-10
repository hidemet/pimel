<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleLikeController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\ServiceAdminController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Admin\RubricController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Admin\ContactMessageAdminController;
use App\Http\Controllers\Admin\NewsletterSubscriptionAdminController;





// Homepage
Route::get('/', HomeController::class)->name('home');
// Controller invokable

// Blog
// Lista articoli (con possibilità di filtri e ordinamento via query string)
Route::get('/blog', [ArticleController::class, 'index'])->name(
    'blog.index'
);

// Singolo articolo (utilizza lo slug come parametro)
Route::get('/blog/{article:slug}', [ArticleController::class, 'show'])
    ->name('blog.show');

// NUOVA ROTTA PER SALVARE I COMMENTI (deve essere protetta da 'auth')
Route::middleware('auth')->group(function () {
    // ... altre rotte auth come profile, logout, articles.toggle_like ...

    Route::post('/blog/{article}/comments', [CommentController::class, 'store'])
        ->name('comments.store'); // {article} qui userà l'ID di default, va bene per il backend
});

// Per il Route Model Binding con lo slug, assicurati che il modello Article abbia:
// public function getRouteKeyName() { return 'slug'; }
// o definisci la chiave nella rotta: Route::get('/blog/{article:slug}', ...);
Route::get('/blog/{article:slug}', [ArticleController::class, 'show'])
    ->name('blog.show');

Route::get('/servizi', [ServiceController::class, 'index'])
    ->name('servizi.index');

// Pagine Statiche gestite da PageController
Route::get('/chi-sono', [PageController::class, 'about'])->name(
    'pages.about'
);
Route::get('/contatti', [PageController::class, 'contactForm'])
    ->name('contatti.form');
Route::get('/newsletter', [PageController::class, 'newsletterForm'])
    ->name('newsletter.form');

// Gestione invio Form di Contatto
Route::post('/contatti', [ContactMessageController::class, 'store'])
    ->name('contatti.store');

// Gestione iscrizione Newsletter
Route::post('/newsletter/subscribe', [
    NewsletterSubscriptionController::class,
    'store'
])->name('newsletter.subscribe');


Route::redirect('/dashboard', '/profile')
    ->middleware(['auth', 'verified', 'redirect.admin'])->name('dashboard');

// Route::get('/dashboard', function () {
//     // Il middleware 'redirect.admin' si occuperà di mandare gli admin alla loro dashboard
//     // Questa closure verrà eseguita solo per gli utenti normali
//     if (Auth::check() && Auth::user()->isAdmin()) { // Doppio controllo, anche se il middleware dovrebbe aver già agito
//         return Redirect::route('admin.dashboard');
//     }
//     return Redirect::route('profile.edit');
// })->middleware(['auth', 'verified', 'redirect.admin'])->name('dashboard');

// Rotte del Profilo (già definite)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::post('/articles/{article}/like', [ArticleLikeController::class, 'toggleLike'])
        ->name('articles.toggle_like');
    // Rotte per la gestione delle preferenze della newsletter dell'utente
    Route::get('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'editPreferences'])
        ->name('profile.newsletter.edit'); // Mostra il form per modificare le preferenze

    Route::patch('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'updatePreferences'])
        ->name('profile.newsletter.update'); // Salva le preferenze modificate

    Route::delete('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'destroySubscription'])
        ->name('profile.newsletter.destroy'); // Annulla l'iscrizione
});
// Includi le rotte di autenticazione di Breeze (login, register, ecc.)
require __DIR__ . '/auth.php';

// --- (FUTURO) ROTTE AREA AMMINISTRATIVA ---
Route::middleware(['auth', 'admin'])
    ->prefix('admin') // Aggiunge 'admin/' all'URL
    ->name('admin.')  // Aggiunge 'admin.' al nome della rotta
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('articles', ArticleAdminController::class);
        Route::resource('rubrics', RubricController::class);
        Route::resource('services', ServiceAdminController::class);


        Route::patch('articles/{article}/status/{newStatus}', [ArticleAdminController::class, 'updateStatus'])
            ->name('articles.status.update')
            ->whereIn('newStatus', ['published', 'draft', 'archived', 'scheduled']);


        Route::get('comments', [CommentAdminController::class, 'index'])->name('comments.index');
        Route::patch('comments/{comment}/update', [CommentAdminController::class, 'update'])->name('comments.update');
        Route::delete('comments/{comment}', [CommentAdminController::class, 'destroy'])->name('comments.destroy');

        Route::get('contact-messages', [ContactMessageAdminController::class, 'index'])->name('contact-messages.index');

        Route::resource('newsletter-subscriptions', NewsletterSubscriptionAdminController::class)
            ->only(['index', 'edit', 'update', 'destroy'])
            ->names('newsletter-subscriptions');
    });
