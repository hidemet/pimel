@props(['articles'])

@if ($articles && $articles->count())
    <div class="article-list-items"> 
        @foreach ($articles as $article)
            <x-shared.article-card :article="$article" />
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <span class="material-symbols-outlined fs-1 text-muted">sentiment_dissatisfied</span>
        <p class="mt-3 mb-0">Nessun articolo trovato.</p>
    </div>
@endif
