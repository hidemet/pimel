@push('meta')
  <script>
    // Definiamo una variabile globale che il nostro blog-index.js potrà usare.
    window.blogIndexUrl = '{{ route('blog.index') }}';
  </script>
@endpush

<x-app-layout>
  <x-slot name="pageHeader">
    <x-layout.page-header title="Esplora il Blog PIMEL">
      <div class="mt-3 mb-3">
        <a
          href="{{ route('newsletter.form') }}"
          class="btn btn-ctc btn-lg"
        >
          <i class="bi bi-envelope me-2"></i>
          Iscriviti alla Newsletter
        </a>
      </div>
    </x-layout.page-header>
  </x-slot>

  <div class="container">
    {{-- Sezione Filtri per Rubrica (contenitore stabile) --}}
    <section class="row justify-content-center">
      <div class="col-12">
        <div class="d-flex flex-wrap justify-content-center gap-2 my-4">
          <input
            type="radio"
            class="btn-check"
            name="rubric_filter"
            id="rubric_all"
            value=""
            autocomplete="off"
            {{ ! $selectedRubricSlug ? 'checked' : '' }}
          />
          <label
            class="btn btn-outline-dark rounded-pill px-3 py-2"
            for="rubric_all"
          >
            <span class="material-symbols-outlined fs-6 me-1 align-middle">
              select_all
            </span>
            Tutte le rubriche
          </label>
          @foreach ($rubrics as $rubric)
            <input
              type="radio"
              class="btn-check"
              name="rubric_filter"
              id="rubric_{{ $rubric->slug }}"
              value="{{ $rubric->slug }}"
              autocomplete="off"
              {{ $selectedRubricSlug == $rubric->slug ? 'checked' : '' }}
            />
            <label
              class="btn btn-outline-dark rounded-pill px-3 py-2"
              for="rubric_{{ $rubric->slug }}"
            >
              {{ $rubric->name }}
            </label>
          @endforeach
        </div>
      </div>
    </section>

    {{-- Contenitore principale per i risultati (colonna più stretta) --}}
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-7">
        {{-- Filtri di Ordinamento --}}
        <div class="mb-4">
          <ul
            class="nav nav-underline justify-content-start"
            id="sortTabs"
            role="tablist"
          >
            <li
              class="nav-item"
              role="presentation"
            >
              <a
                class="nav-link {{ $sortByInput === 'published_at_desc' ? 'active' : '' }}"
                href="{{ route('blog.index', ['ordina_per' => 'published_at_desc', 'rubrica' => $selectedRubricSlug]) }}"
              >
                Ultimi articoli
              </a>
            </li>
            <li
              class="nav-item"
              role="presentation"
            >
              <a
                class="nav-link {{ $sortByInput === 'likes_desc' ? 'active' : '' }}"
                href="{{ route('blog.index', ['ordina_per' => 'likes_desc', 'rubrica' => $selectedRubricSlug]) }}"
              >
                Più piaciuti
              </a>
            </li>
            <li
              class="nav-item"
              role="presentation"
            >
              <a
                class="nav-link {{ $sortByInput === 'comments_desc' ? 'active' : '' }}"
                href="{{ route('blog.index', ['ordina_per' => 'comments_desc', 'rubrica' => $selectedRubricSlug]) }}"
              >
                Più commentati
              </a>
            </li>
          </ul>
        </div>

        {{-- Contenitore DINAMICO per la lista degli articoli --}}
        <div id="article-list-container">
          @include('components.shared._article-list-content', ['articles' => $articles])
        </div>

        {{-- Contenitore DINAMICO per la paginazione --}}
        <div
          id="pagination-container"
          class="d-flex justify-content-start mt-5 mb-4"
        >
          @if ($articles->hasPages())
            {{ $articles->appends(request()->query())->links() }}
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
