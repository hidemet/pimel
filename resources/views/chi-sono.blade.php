<x-app-layout>
  @section('title', 'Chi Sono - Manuela Donati, Pedagogista PIMEL')
  @section('description', 'Scopri chi è Manuela Donati, pedagogista ed educatrice, fondatrice di PIMEL - Pedagogia In Movimento.')

  <x-layout.page-header title="Chi Sono" />

  <section class="py-4 py-md-5">
    <div class="container">
      <div class="row justify-content-center align-items-center g-4">
        <div class="col-auto">
          <img
            src="{{ asset('assets/img/foto-profilo.png') }}"
            alt="Manuela Donati - Pedagogista ed Educatrice"
            class="img-fluid rounded-circle shadow"
            style="width: 150px; height: 150px; object-fit: cover"
          />
        </div>
        <div class="col-md-auto text-center text-md-start">
          <h2 class="h3 fw-semibold mb-1">Dott.ssa Manuela Donati</h2>
          <p class="lead text-muted mb-0">Pedagogista ed Educatrice</p>
        </div>
      </div>
    </div>
  </section>

  <div class="container mt-4 mb-5">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-7">
        <article>
          <section class="mb-5">
            <p class="lead">
              Ciao! 👋 Sono Manuela, fondatrice del progetto
              <strong>Pedagogia in Movimento (PIMEL)</strong>
              . Ho creato questo spazio con un'idea semplice: rendere la
              pedagogia uno strumento vivo e accessibile, un supporto concreto
              per genitori, educatori e chiunque affronti le sfide della
              crescita.
            </p>
            <p>
              La mia formazione include una
              <strong>Laurea in Scienze dell'Educazione</strong>
              e una
              <strong>Laurea Magistrale in Scienze Pedagogiche</strong>
              , che costituiscono la base solida su cui unisco rigore
              scientifico ed empatia nel mio approccio.
            </p>
            <p>
              Spero che PIMEL possa diventare per te un punto di riferimento.
              Non esitare a esplorare il
              <a href="{{ route('blog.index') }}">Blog</a>
              o a scoprire i
              <a href="{{ route('servizi.index') }}">Servizi</a>
              che offro.
            </p>
          </section>

          <div class="text-end mt-5 pt-4">
            <p class="text-muted mb-3">Un caro saluto,</p>
            <img
              src="{{ asset('assets/img/firma_manuela_donati.png') }}"
              alt="Firma di Manuela Donati"
              class="img-fluid signature-img"
            />
          </div>
        </article>
      </div>
    </div>
  </div>
</x-app-layout>
