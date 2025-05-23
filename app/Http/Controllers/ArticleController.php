<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Rubric;
use App\Models\User; // Assicurati che sia importato
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request ): View {
        $selectedRubricSlug = $request->query( 'rubrica' );
        $sortBy             = $request->query( 'ordina_per',
            'published_at_desc' ); // Default a 'ultimi pubblicati'

        // Inizia la query base per gli articoli
        $articlesQuery = Article::query()
            ->where( 'status', 'published' )
            ->whereNotNull( 'published_at' )
            ->where( 'published_at', '<=', now() );

        // Applica il filtro per rubrica, se presente
        if ( $selectedRubricSlug ) {
            $articlesQuery->whereHas( 'rubrics', function ( $query ) use
                ( $selectedRubricSlug ) {
                    $query->where( 'slug', $selectedRubricSlug );
                } );
        }

        // Eager load delle relazioni necessarie per la card e conteggi per l'ordinamento
        $articlesQuery->with( ['rubrics'] )
                      // Per visualizzare le rubriche nella card
            ->withCount( [ // Per i conteggi di like e commenti
                'likes',
                'comments' => function ( $query ) {
                    $query->where( 'is_approved', true );
                    // Conta solo i commenti approvati
                },
            ] );

        // Applica l'ordinamento
        switch ( $sortBy ) {
        case 'likes_desc':
            $articlesQuery->orderBy( 'likes_count', 'desc' );
            break;
        case 'comments_desc':
            $articlesQuery->orderBy( 'comments_count', 'desc' );
            break;
        case 'published_at_asc':
            $articlesQuery->orderBy( 'published_at', 'asc' );
            break;
        case 'published_at_desc':
        default:
            $articlesQuery->orderBy( 'published_at', 'desc' );
            break;
        }

        // Esegui la paginazione
        $articles = $articlesQuery->paginate( 10 );

        // Recupera tutte le rubriche per il menu dei filtri
        $rubrics = Rubric::orderBy( 'name' )->get();

        return view( 'blog.index', [
            'articles'           => $articles,
            'rubrics'            => $rubrics,
            'selectedRubricSlug' => $selectedRubricSlug,
            'sortBy'             => $sortBy,
        ] );
    }

    /**
     * Display the specified resource.
     */
    public function show( Article $article ): View// Route Model Binding
    {
        // Se l'articolo NON è pubblicato
        if ( $article->status !== 'published' ) {

            // E l'utente NON è autenticato OPPURE (è autenticato MA NON è admin)
            $user = Auth::user();
            if ( !$user || ( $user instanceof User && !$user->isAdmin() ) ) {
                abort( 404 ); // Allora accesso negato
            }
        }

        // Eager load delle relazioni per la vista del singolo articolo
        $article->load( [
            'author',  // Autore dell'articolo
            'rubrics', // Rubriche dell'articolo
            'comments' => function ( $query ) {
                // Commenti approvati e le loro risposte approvate
                $query->where( 'is_approved', true )
                    ->whereNull( 'parent_id' )
                // Solo commenti di primo livello
                    ->with( ['user', 'replies' => function ( $replyQuery ) {
                        // Carica l'autore del commento e le risposte
                        $replyQuery->where( 'is_approved', true )
                            ->with( 'user' )
                        // Carica l'autore della risposta
                            ->orderBy( 'created_at', 'asc' );
                    }] )
                    ->orderBy( 'created_at', 'desc' );
            },
            'likes',
            // Per il conteggio dei like e potenzialmente per vedere chi ha messo like
        ] );

        // Recupera articoli correlati (basati su rubriche comuni)
        $relatedArticles = Article::where( 'status', 'published' )
            ->where( 'id', '!=', $article->id ) // Escludi l'articolo corrente
            ->whereHas( 'rubrics', function ( $query ) use ( $article ) {
                $query->whereIn( 'rubrics.id', $article->rubrics->pluck( 'id' ) );
            } )
            ->with( ['rubrics'] )
        // Eager load rubriche anche per i correlati (per la card compatta)
            ->withCount( ['likes', 'comments' => fn( $q ) => $q
                    ->where( 'is_approved', true )] ) // E conteggi
            ->orderBy( 'published_at', 'desc' )
            ->take( 3 )
            ->get();

        return view( 'blog.show', [
            'article'         => $article,
            'relatedArticles' => $relatedArticles,
        ] );
    }

    // I metodi create, store, edit, update, destroy verranno implementati
    // per l'area amministrativa.
}