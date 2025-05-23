<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class HomeController extends Controller {
    /**
     * Mostra la homepage.
     */
    public function __invoke(): View {
        $latestArticles = Article::with( [
            'rubrics',
            // Per $article->rubrics->isNotEmpty() e $article->rubrics->first()->name

            // 'likes', // Per $article->likes->count() - meglio withCount

            // 'comments' // Per $article->comments->where(...)->count() - meglio withCount
        ] )
            ->withCount( [ // Più efficiente per i conteggi
                'likes',
                'comments' => function ( $query ) {
                    $query->where( 'is_approved', true );
                },
            ] )
            ->where( 'status', 'published' )
            ->whereNotNull( 'published_at' )
            ->where( 'published_at', '<=', now() )
            ->orderBy( 'published_at', 'desc' )
            ->take( 3 )
            ->get();

        return view( 'home', [
            'latestArticles' => $latestArticles,
        ] );
    }
}