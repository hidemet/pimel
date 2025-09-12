<x-app-layout>
  @section('title', 'Pedagogia In Movimento - Conoscere, Capire, Educare')
  @section('description', 'Manuela Donati, Pedagogista ed Educatrice, divulga la pedagogia in Italia con il progetto Pedagogia in Movimento.')
  <section class="bg-herosection text-white pt-5">
    <div class="container">
      <div class="row align-items-center gy-4 gx-lg-5">
        <div class="col-lg-7 text-center text-lg-start mb-4">
          <h1 class="display-6 fw-medium mb-4">
            Benvenuti su Pedagogia in Movimento
          </h1>
          <p class="h5 fw-normal mb-4">
            👋 Ciao, io sono Manuela Donati, una pedagogista ed educatrice. Ho
            fondato il progetto Pedagogia in Movimento per divulgare la
            pedagogia in Italia.
          </p>
          <p class="lead mb-4">
            Qui trovi risorse pratiche, approfondimenti e supporto pensati per
            genitori, educatori e studenti.
          </p>
          <div
            class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-lg-start"
          >
            <a
              href="{{ route('blog.index') }}"
              class="btn btn-light btn-lg px-4"
            >
              Esplora il Blog
            </a>
            <a
              href="{{ route('servizi.index') }}"
              class="btn btn-outline-light btn-lg px-4"
            >
              Scopri i Servizi
            </a>
          </div>
        </div>
        <div class="col-lg-4 text-center">
          <img
            src="{{ asset('assets/img/pedagogista-hero.png') }}"
            alt="Dott.ssa Manuela Donati - Pedagogista ed Educatrice"
            class="img-fluid hero-image-custom"
          />
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row gx-lg-5">
        <div class="col-lg-4">
          <h2 class="h3 fw-semibold mb-3">Ultimi articoli dal Blog</h2>
        </div>

        <div class="col-lg-8">
          @if ($latestArticles->isNotEmpty())
            <div class="list-group list-group-flush">
              @foreach ($latestArticles as $article)
                <x-shared.article-card :article="$article" />
              @endforeach
            </div>

            <div class="mt-4">
              <a
                href="{{ route('blog.index') }}"
                class="btn btn-outline-dark"
              >
                Vai a tutti gli articoli
                <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          @else
            <div class="text-center py-5">
              <p>
                Nessun articolo disponibile al momento.
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
</x-app-layout>
