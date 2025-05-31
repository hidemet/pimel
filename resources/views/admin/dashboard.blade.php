{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout :bodyClass="'bg-body-private-default'"> {{-- Sfondo specifico per area privata --}}
    @section('title', 'Dashboard Admin - PIMEL')

    <x-slot name="pageHeader">
        <x-layout.page-header title="Pannello Amministrazione"
            subtitle="Gestisci i contenuti e le funzionalità del sito." />
    </x-slot>

    <div class="container py-4 py-md-5">
        <div class="row g-4">
            {{-- Esempio di card di navigazione --}}
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <span class="material-symbols-outlined fs-1 text-primary mb-2">article</span>
                        <h5 class="card-title">Gestione Articoli</h5>
                        <p class="card-text small text-muted">Crea, modifica ed elimina gli articoli del blog.</p>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-primary mt-auto stretched-link">Vai
                            agli Articoli</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <span class="material-symbols-outlined fs-1 text-secondary mb-2">category</span>
                        <h5 class="card-title">Gestione Rubriche</h5>
                        <p class="card-text small text-muted">Organizza le categorie tematiche del blog.</p>
                        <a href="{{-- route('admin.rubrics.index') --}}" class="btn btn-secondary mt-auto stretched-link disabled">Vai
                            alle Rubriche</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <span class="material-symbols-outlined fs-1 text-info mb-2">comment</span>
                        <h5 class="card-title">Moderazione Commenti</h5>
                        <p class="card-text small text-muted">Approva o elimina i commenti degli utenti.</p>
                        <a href="{{-- route('admin.comments.index') --}}" class="btn btn-info mt-auto stretched-link disabled">Vai ai
                            Commenti</a>
                    </div>
                </div>
            </div>
            {{-- Aggiungi altre card per Servizi, Newsletter, Contatti, Pagina "Chi Sono" --}}
        </div>
    </div>
</x-app-layout>
