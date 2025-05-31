<x-app-layout>

    @section('title', 'Pedagogia In Movimento - Conoscere, Capire, Educare') {{-- Titolo aggiornato per il browser --}}
    @section('description',
        'Manuela Donati, Pedagogista ed Educatrice, divulga la pedagogia in Italia con PIMEL.
        Risorse e supporto per genitori, educatori e studenti.') {{-- Descrizione aggiornata --}}
        <section class=" d-flex flex-column justify-content-center pt-5 ">
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
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-lg-start">
                            <a href="{{ route('blog.index') }}" class="btn btn-dark btn-lg  px-4">Esplora il
                                Blog</a>
                            <a href="{{ route('servizi.index') }}" class="btn btn-outline-dark btn-lg  px-4">Scopri i
                                Servizi</a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center align-self-lg-end">
                        <img src="{{ asset('assets/img/pedagogista-hero.png') }}"
                            alt="Dott.ssa Manuela Donati - Pedagogista ed Educatrice" class="img-fluid hero-image-custom">
                    </div>
                </div>
            </div>
        </section>

        <section class="d-flex flex-column py-5">
            <div class="container">
                <div class="row"> {{-- Riga aggiunta per il corretto layout Bootstrap --}}

                    <div class="col-lg-3">
                        <h3 class="mb-4 mb-lg-0">Ultimi Articoli dal Blog</h3>
                    </div>
                    <div class="col-lg-9">
                        <div class="list-group list-group-flush mb-4">
                            @forelse ($latestArticles->take(5) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}"
                                    class="list-group-item bg-transparent py-3">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-sm-7 col-lg-8 mb-2 mb-sm-0">
                                            <p class="fw-medium mb-0">
                                                {{ $article->title }}
                                            </p>
                                        </div>
                                        <div class="col-6 col-sm-3 col-lg-2 text-start text-sm-end mb-1 mb-sm-0">
                                            @if ($article->rubrics->isNotEmpty())
                                                <small class="text-muted d-block">
                                                    {{ $article->rubrics->first()->name }}
                                                </small>
                                            @else
                                                <small class="text-muted d-block">&nbsp;</small>
                                            @endif
                                        </div>
                                        <div class="col-6 col-sm-2 col-lg-2 text-end">
                                            @if ($article->published_at)
                                                <small class="text-muted text-nowrap d-block">
                                                    {{ $article->published_at->translatedFormat('d/M/Y') }}
                                                </small>
                                            @else
                                                <small class="text-muted d-block">&nbsp;</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="list-group-item">
                                    <p class="text-muted mb-0">Nessun articolo disponibile al momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
        </section>

    </x-app-layout>
