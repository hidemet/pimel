<div class="p-5 mb-4 bg-light rounded-3 text-center">
    {{-- Assicurati che il percorso dell'asset sia corretto e che l'immagine esista in public/assets/img/ --}}
    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}" alt="PIMEL Logo" height="70" class="mb-3">
    <h1 class="display-5 fw-bold">Pedagogia In Movimento</h1>
    <p class="fs-4 col-lg-8 mx-auto">
        Il tuo punto di riferimento per la pedagogia in Italia. Risorse per genitori, educatori e studenti.
    </p>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
        <a href="{{ route('blog.index') }}" class="btn btn-primary btn-lg px-4 gap-3">Esplora il Blog</a>
        <a href="{{ route('servizi.index') }}" class="btn btn-outline-secondary btn-lg px-4">Scopri i Servizi</a>
    </div>
</div>