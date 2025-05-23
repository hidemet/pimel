<x-app-layout>
    @section('title', 'Chi Sono - Manuela Donati, Pedagogista PIMEL')
    @section('description',
        'Scopri chi è Manuela Donati, pedagogista ed educatrice, fondatrice di PIMEL - Pedagogia In
        Movimento.')

    {{-- Usa il componente page-header per consistenza --}}
    <x-layout.page-header title="Chi Sono" />

    {{-- Header con foto e presentazione --}}
    <section class=" py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center align-items-center g-4">
                <div class="col-auto">
                    <img src="{{ asset('assets/img/foto-profilo.png') }}"
                        alt="Manuela Donati - Pedagogista ed Educatrice"
                        class="img-fluid rounded-circle shadow"
                        style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-auto text-center text-md-start">
                    <h2 class="h3 fw-semibold mb-1">Dott.ssa Manuela Donati</h2>
                    <p class="lead text-muted mb-0">Pedagogista ed Educatrice</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenuto principale centrato --}}
    <div class="container page-content-container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <article>
                    <section class="mb-5">
                        <p class="lead">
                            Ciao! 👋 Sono Manuela, fondatrice del progetto <strong>Pedagogia in Movimento!</strong>. Se
                            sei qui, probabilmente sei un genitore, un educatore, uno studente o semplicemente
                            incuriosito/a dal mondo dell'educazione e della pedagogia, sei nel posto giusto.
                        </p>
                        <p>
                            Ho creato Pimel con due obiettivi chiari: valorizzare la figura del pedagogista e
                            dell'educatore in Italia e offrire strumenti concreti per affrontare le sfide educative quotidiane.
                            "Pedagogia In Movimento" perché credo che la pedagogia debba <em>muoversi</em> verso le persone,
                            diventando strumento vivo e accessibile.
                        </p>
                    </section>

                    <section class="mb-5">
                        <h3 class="h4 fw-semibold mb-3">Perché Pedagogia in Movimento</h3>
                        <p>Ho creato Pimel con due obiettivi chiari:</p>
                        <ol class="ps-3 mb-4">
                            <li class="mb-2">
                                <strong>Valorizzare la Pedagogia</strong> e promuovere la figura del pedagogista e
                                dell'educatore in Italia. Professioni fondamentali all'interno di una società e spesso
                                poco conosciute al grande pubblico.
                            </li>
                            <li class="mb-2">
                                <strong>Sostenere le Famiglie</strong> nelle sfide educative quotidiane e nelle grandi
                                trasformazioni di questa epoca.
                            </li>
                        </ol>

                        <blockquote class="blockquote border-start border-primary border-4 ps-3 py-2 rounded-end">
                            <p class="mb-2">Credo che la pedagogia debba <em>muoversi</em> verso le persone, uscire
                                dagli ambienti strettamente accademici per diventare strumento vivo e accessibile.</p>
                            <footer class="blockquote-footer">
                                <cite title="Manuela Donati">Manuela Donati</cite>
                            </footer>
                        </blockquote>

                        <p>
                            Ed è per questo che ho creato "Pedagogia In Movimento", per dare spazio ad un movimento
                            che mira a <em>promuovere</em> una maggiore consapevolezza sul valore dell'educazione e a
                            <em>sostenere</em> chi, ogni giorno, si impegna in questo avvincente compito.
                        </p>
                    </section>

                    <section class="mb-5">
                        <h3 class="h4 fw-semibold mb-4">Formazione e Titoli</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0">
                                    <div class="card-body">
                                        <h4 class="h6 fw-bold text-primary mb-2">Laurea in Scienze dell'Educazione</h4>
                                        <p class="small text-muted mb-0">Università degli Studi di Bergamo</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0">
                                    <div class="card-body">
                                        <h4 class="h6 fw-bold text-primary mb-2">Laurea Magistrale in Scienze Pedagogiche</h4>
                                        <p class="small text-muted mb-0">
                                            Università degli Studi di Bergamo • 
                                            <span class="fw-medium text-success">110 e Lode</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5">
                        <h3 class="h4 fw-semibold mb-4">Il Mio Approccio in Pillole</h3>
                        <div class="row g-4">
                            @php
                                $approcci = [
                                    [
                                        'icon' => 'psychology',
                                        'title' => 'Centrato sulla persona',
                                        'text' => 'Ogni individuo è unico. Non ci sono soluzioni preconfezionate, ma percorsi da costruire insieme.',
                                    ],
                                    [
                                        'icon' => 'emoji_objects',
                                        'title' => 'Concreto e pratico',
                                        'text' => 'Basato su evidenze scientifiche tenendo conto del contesto',
                                    ],
                                    [
                                        'icon' => 'hearing',
                                        'title' => 'In ascolto attivo',
                                        'text' => "L'ascolto attivo è la base di ogni relazione educativa efficace. Prima di proporre, cerco di capire.",
                                    ],
                                    [
                                        'icon' => 'trending_up',
                                        'title' => 'Orientato alla crescita',
                                        'text' => 'Aiutarti a scoprire, mobilitare e valorizzare le tue risorse interne e quelle dei tuoi figli o studenti.',
                                    ],
                                    [
                                        'icon' => 'sentiment_very_satisfied',
                                        'title' => 'Positivo e Propositivo',
                                        'text' => "Anche nelle difficoltà, cerco sempre di individuare le opportunità di apprendimento e di crescita.",
                                    ],
                                ];
                            @endphp
                            @foreach ($approcci as $approccio)
                                <div class="col-lg-6">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <span class="material-symbols-outlined text-primary fs-4">
                                                        {{ $approccio['icon'] }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="card-title h6 fw-bold mb-2">{{ $approccio['title'] }}</h5>
                                                <p class="card-text text-muted small mb-0">{{ $approccio['text'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="mb-5">
                        <h3 class="h4 fw-semibold mb-3">Cosa Trovi su Pedagogia in Movimento</h3>
                        <p>
                            Su questo sito troverai articoli nel <a href="{{ route('blog.index') }}"
                                class="fw-semibold text-decoration-none">Blog</a> pensati per offrirti
                            spunti di riflessione e consigli pratici. Potrai anche scoprire i 
                            <a href="{{ route('servizi.index') }}" class="fw-semibold text-decoration-none">Servizi di Consulenza</a>
                            che offro, pensati per supportarti in modo personalizzato.
                        </p>
                        <p>
                            Spero che PIMEL possa diventare per te un punto di riferimento e un luogo dove sentirti accolto/a.
                        </p>
                    </section>

                    {{-- CTA finale --}}
                    <div class="card border-0 bg-primary bg-opacity-10 rounded-4 shadow-sm">
                        <div class="card-body text-center py-5">
                            <h4 class="card-title text-primary mb-3">
                                Hai domande o vuoi iniziare un percorso insieme?
                            </h4>
                            <p class="card-text mb-4">
                                Non esitare a contattarmi. Sarò felice di conoscerti e capire come posso aiutarti.
                            </p>
                            <a href="{{ route('contatti.form') }}" class="btn btn-ctc btn-lg rounded-pill px-4">
                                <span class="material-symbols-outlined fs-6 me-2">contact_support</span>
                                Contattami Ora
                            </a>
                        </div>
                    </div>

                    {{-- Firma --}}
                    <div class="text-center mt-5 pt-4">
                        <p class="text-muted mb-3">Un caro saluto,</p>
                        <img src="{{ asset('assets/img/firma_manuela_donati.png') }}" 
                             alt="Firma di Manuela Donati"
                             class="img-fluid signature-img">
                    </div>
                </article>
            </div>
        </div>
    </div>
</x-app-layout>