{{-- resources/views/home.blade.php --}}
<x-app-layout>
    @section('title', 'Pedagogia In Movimento - Conoscere, Capire, Educare') {{-- Titolo aggiornato per il browser --}}
    @section('description',
        'Manuela Donati, Pedagogista ed Educatrice, divulga la pedagogia in Italia con PIMEL.
        Risorse e supporto per genitori, educatori e studenti.') {{-- Descrizione aggiornata --}}

        <div class="home-header-bg">
            <section class="hero-section d-flex flex-column justify-content-center pt-5">
                <div class="container">
                    <div class="row align-items-center align-items-lg-end gy-4 gx-lg-5">
                        <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0 pb-lg-4">
                            <h1 class="display-6 fw-medium mb-3">Pedagogia in Movimento: Conoscere, Capire, Educare</h1>
                            <p class="h5 fw-normal mb-4">
                                👋 Ciao, sono Manuela Donati, Pedagogista ed Educatrice. Ho fondato il progetto "Pedagogia
                                in Movimento" per divulgare la pedagogia in Italia. Credo fermamente che possa contribuire a
                                migliorare il benessere della nostra società e delle famiglie.
                            </p>
                            <p class="lead mb-4">
                                Qui trovi risorse pratiche, approfondimenti e supporto pensati per genitori, educatori e
                                studenti.
                            </p>
                            {{-- FINE NUOVO TITOLO E TESTO --}}

                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-lg-start">
                                <a href="{{ route('blog.index') }}" class="btn btn-ctc btn-lg rounded-pill px-4">Esplora il
                                    Blog</a>
                                <a href="{{ route('servizi.index') }}"
                                    class="btn btn-outline-light btn-lg rounded-pill px-4">Scopri i Servizi</a>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center align-self-lg-end">
                            <img src="{{ asset('assets/img/pedagogista-hero.png') }}"
                                alt="Dott.ssa Manuela Donati - Pedagogista ed Educatrice"
                                class="img-fluid hero-image-custom">
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="container page-content-container mt-5 mb-5">
            <x-layout.two-column-sidebar>
                {{-- Questo contenuto andrà nello $slot della colonna principale --}}
                <h3 class="mb-4">Ultimi Articoli dal Blog</h3>
                <hr class="mb-4">

                <x-shared.article-list :articles="$latestArticles" />

                <div class="mt-4 mb-5 text-end">
                    <a href="{{ route('blog.index') }}" class="btn btn-primary rounded-pill">
                        Tutti gli articoli
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </x-layout.two-column-sidebar>
        </div>

    </x-app-layout>
