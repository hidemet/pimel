{{-- resources/views/components/shared/newsletter-card.blade.php --}}
<div class="card h-100 rounded-4 p-2 bg-transparent">
    <div class="card-body text-center">
        <h5 class="card-title mb-3">
            Resta Aggiornato
        </h5>
        <p class="card-text text-muted mb-4">
            Ricevi i nuovi articoli e consigli direttamente nella tua email.
        </p>
        <a href="{{ route('newsletter.form') }}" class="btn btn-dark w-100">
            Iscriviti Gratis
        </a>
    </div>
</div>
