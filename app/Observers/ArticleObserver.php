<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleObserver
{
    public function saving(Article $article): void
    {
        // Genera/Aggiorna lo slug se il titolo è cambiato o l'articolo è nuovo
        if ($article->isDirty('title') || !$article->exists) {
            $slug = Str::slug($article->title);
            $originalSlug = $slug;
            $counter = 1;
            // Assicura l'unicità dello slug
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $article->slug = $slug;
        }

        // Genera description di default se non fornita o vuota
        if (empty($article->description)) {
            $article->description = Str::words(strip_tags($article->body), 30, '...');
        }
    }
}
