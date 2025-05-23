{{-- resources/views/components/shared/service-promo-card.blade.php --}}
<div class="card sm h-100 rounded-5">
    <div class="card-body text-center">
        <h5 class="card-title text-primary mb-3">
            Consulenza Pedagogica
        </h5>
        <p class="card-text text-muted mb-4">
            Supporto personalizzato per affrontare le sfide educative.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ route('servizi.index') }}" class="btn btn-primary rounded-pill">
                Scopri i Servizi
            </a>
            <a href="{{ route('contatti.form') }}" class="btn btn-outline-primary rounded-pill btn-sm">
                Richiedi Info
            </a>
        </div>
    </div>
</div>
