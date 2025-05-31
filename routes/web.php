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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Qui puoi registrare le rotte web per la tua applicazione. Queste
| rotte sono caricate dal RouteServiceProvider e tutte saranno
| assegnate al gruppo di middleware "web".
|
 */

// Homepage
Route::get( '/', HomeController::class )->name( 'home' );
// Controller invokable

// Blog
// Lista articoli (con possibilità di filtri e ordinamento via query string)
Route::get( '/blog', [ArticleController::class, 'index'] )->name( 'blog.index'
);

// Singolo articolo (utilizza lo slug come parametro)
Route::get('/blog/{article:slug}', [ArticleController::class, 'show'])
    ->name('blog.show');

// NUOVA ROTTA PER SALVARE I COMMENTI (deve essere protetta da 'auth')
Route::middleware('auth')->group(function () {
    // ... altre rotte auth come profile, logout, articles.toggle_like ...

    Route::post('/blog/{article}/comments', [CommentController::class, 'store'])
        ->name('comments.store'); // {article} qui userà l'ID di default, va bene per il backend
    // NUOVA ROTTA PER SALVARE LE RISPOSTE AI COMMENTI
    Route::post('/blog/{article}/comments/{parentComment}/reply', [CommentController::class, 'storeReply'])
        ->name('comments.store_reply');
});

// Per il Route Model Binding con lo slug, assicurati che il modello Article abbia:
// public function getRouteKeyName() { return 'slug'; }
// o definisci la chiave nella rotta: Route::get('/blog/{article:slug}', ...);
Route::get( '/blog/{article:slug}', [ArticleController::class, 'show'] )
    ->name( 'blog.show' );

// Servizi
Route::get( '/servizi', [ServiceController::class, 'index'] )
    ->name( 'servizi.index' );

// Pagine Statiche gestite da PageController
Route::get( '/chi-sono', [PageController::class, 'about'] )->name(
    'pages.about' );
Route::get( '/contatti', [PageController::class, 'contactForm'] )
    ->name( 'contatti.form' );
Route::get( '/newsletter', [PageController::class, 'newsletterForm'] )
    ->name( 'newsletter.form' );

// Gestione invio Form di Contatto
Route::post( '/contatti', [ContactMessageController::class, 'store'] )
    ->name( 'contatti.store' );

// Gestione iscrizione Newsletter
Route::post( '/newsletter/subscribe', [NewsletterSubscriptionController::class,
    'store'] )->name( 'newsletter.subscribe' );

// --- ROTTE DI AUTENTICAZIONE E PROFILO (definite da Breeze e da te) ---

// NUOVA VERSIONE (DA USARE)
Route::get('/dashboard', function () {
    return view('dashboard'); // Ora questa closure fa solo una cosa: mostra la vista dashboard
})->middleware(['auth', 'verified', 'redirect.admin'])->name('dashboard');
// Il middleware 'redirect.admin' si occuperà del reindirizzamento SE l'utente è un admin

// Rotte del Profilo (già definite)
Route::middleware( 'auth' )->group( function () {
    Route::get( '/profile', [ProfileController::class, 'edit'] )
        ->name( 'profile.edit' );
    Route::patch( '/profile', [ProfileController::class, 'update'] )
        ->name( 'profile.update' );
    Route::delete( '/profile', [ProfileController::class, 'destroy'] )
        ->name( 'profile.destroy' );
   Route::post('/articles/{article}/like', [ArticleLikeController::class, 'toggleLike'])
        ->name('articles.toggle_like');
    } );

// Includi le rotte di autenticazione di Breeze (login, register, ecc.)
require __DIR__ . '/auth.php';

// --- (FUTURO) ROTTE AREA AMMINISTRATIVA ---
// Mettiamo un placeholder per il pannello admin
Route::middleware( ['auth', 'admin'] )->prefix( 'admin' )->name( 'admin.' )
        ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // Qui aggiungeremo le altre rotte admin (CRUD articoli, rubriche, ecc.)
        // Esempio: Route::resource('articles', AdminArticleController::class);
          Route::resource('articles', ArticleAdminController::class);
    } );