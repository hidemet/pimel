@props(['author'])
<div class="author-info my-5 p-4 rounded-4 bg-light shadow-sm">
    <div class="row align-items-center">
        <div class="col-md-2 text-center mb-3 mb-md-0">
            <span class="material-symbols-outlined fs-1 text-secondary">account_circle</span>
        </div>
        <div class="col-md-10">
            <h4 class="mb-1 h5 fw-semibold">Scritto da {{ $author->name }}</h4>
            @if ($author->isAdmin())
                <p class="text-muted mb-2 small">Pedagogista ed Educatrice, Autrice di PIMEL</p>
                <p class="mb-0 small">Specializzata nello sviluppo infantile, BES e DSA...</p>
            @else
                <p class="text-muted mb-2 small">Utente della Community PIMEL</p>
            @endif
        </div>
    </div>
</div>
