{{-- resources/views/blog/partials/_author-box.blade.php --}}
@props(['author'])

<div class="author-info my-5 p-4 border-top border-dark">
    <div class="row align-items-center g-3"> {{-- Aggiunto g-3 per spaziatura --}}
        <div class="col-md-2 text-center mb-3 mb-md-0">
            @if ($author->isAdmin())
                <img src="{{ asset('assets/img/foto-profilo.png') }}" alt="Foto profilo di {{ $author->name }}"
                    class="img-fluid rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                {{-- Ho mantenuto uno style inline minimale per garantire la forma circolare e le dimensioni.
                          Potresti definire una classe CSS se preferisci: .author-avatar { width: 80px; height: 80px; object-fit: cover; } --}}
            @else
                <span class="material-symbols-outlined fs-1 text-secondary"
                    style="font-size: 5rem !important;">account_circle</span>
                {{-- Aumentata la dimensione dell'icona generica per bilanciare --}}
            @endif
        </div>
        <div class="col-md-10">
            <h4 class="mb-1 h5 fw-semibold">{{ $author->name }}</h4>
            @if ($author->isAdmin())
                <p class="text-muted mb-2 small">Pedagogista ed Educatrice, Autrice di PIMEL</p>
                {{-- Potresti aggiungere qui una breve bio se presente nel modello User o in una tabella collegata --}}
                <p class="mb-0 small">Specializzata nello sviluppo infantile, BES, DSA e supporto alla genitorialità.
                </p>
            @else
                <p class="text-muted mb-0 small">Utente della Community PIMEL</p>
            @endif
        </div>
    </div>
</div>
