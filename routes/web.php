<?php

use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Admin\ContactMessageAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsletterSubscriptionAdminController;
use App\Http\Controllers\Admin\RubricController;
use App\Http\Controllers\Admin\ServiceAdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Rotte del Blog
Route::get('/blog', [ArticleController::class, 'index'])->name('blog.index');
Route::get('/blog/{article:slug}', [ArticleController::class, 'show'])->name('blog.show');

// Servizi
Route::get('/servizi', [ServiceController::class, 'index'])->name('servizi.index');

// Pagine statiche
Route::get('/chi-sono', [PageController::class, 'about'])->name('pages.about');
Route::get('/contatti', [PageController::class, 'contactForm'])->name('contatti.form');
Route::get('/newsletter', [PageController::class, 'newsletterForm'])->name('newsletter.form');

// Form pubblici
Route::post('/contatti', [ContactMessageController::class, 'store'])->name('contatti.store');
Route::post('/newsletter/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.subscribe');

// Reindirizzamento dashboard
Route::redirect('/dashboard', '/profile')->middleware(['auth', 'verified', 'redirect.admin'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Gestione profilo
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Articoli
    Route::post('/articles/{article}/like', [ArticleLikeController::class, 'toggleLike'])->name('articles.toggle_like');
    Route::post('/blog/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Newsletter
    Route::get('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'editPreferences'])->name('profile.newsletter.edit');
    Route::patch('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'updatePreferences'])->name('profile.newsletter.update');
    Route::delete('/profile/newsletter-preferences', [NewsletterSubscriptionController::class, 'destroySubscription'])->name('profile.newsletter.destroy');
});
require __DIR__ . '/auth.php';

// Rotte Area Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Gestione articoli, rubriche, servizi
    //  Resource Routing: genera automaticamente le 7 rotte standard per le operazioni CRUD su una risorsa seguendo le convenzioni RESTful.
    Route::resource('articles', ArticleAdminController::class)->except(['show']);
    Route::resource('rubrics', RubricController::class)->except(['show']);
    Route::resource('services', ServiceAdminController::class);

    // Gestione commenti
    Route::get('comments', [CommentAdminController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/update', [CommentAdminController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentAdminController::class, 'destroy'])->name('comments.destroy');

    // Messaggi e newsletter
    Route::get('contact-messages', [ContactMessageAdminController::class, 'index'])->name('contact-messages.index');
    Route::get('newsletter-subscriptions', [NewsletterSubscriptionAdminController::class, 'index'])->name('newsletter-subscriptions.index');
});
