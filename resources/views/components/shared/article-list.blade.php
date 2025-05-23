@props(['articles'])

@if ($articles && $articles->count())
    <div class="article-list-items"> {{-- Aggiunto un div wrapper opzionale per gli item, se serve per styling --}}
        @foreach ($articles as $article)
            <x-shared.article-card :article="$article" />
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <span class="material-symbols-outlined fs-1 text-muted">sentiment_dissatisfied</span>
        <p class="mt-3 mb-0">Nessun articolo trovato.</p>
        {{-- Potremmo aggiungere un link generico opzionale, ma per ora lo teniamo semplice --}}
        {{-- @if (Route::has('blog.index'))
            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">Vedi tutti gli articoli</a>
        @endif --}}
    </div>
@endif
