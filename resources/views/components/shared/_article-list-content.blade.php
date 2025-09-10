@props(['articles'])

@if ($articles->isNotEmpty())
    <div class="list-group list-group-flush list-group-item-action active mb-3">
        @foreach ($articles as $article)
            <x-shared.article-card :article="$article" />
        @endforeach
    </div>
@else
    <div class="text-center py-5 my-4 border rounded-3 bg-light">
        <span class="material-symbols-outlined fs-1 text-muted">sentiment_dissatisfied</span>
        <p class="mt-3 mb-0">Nessun articolo trovato per i criteri selezionati.</p>
    </div>
@endif