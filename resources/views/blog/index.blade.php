<x-app-layout >
    <x-slot name="pageHeader">
        <x-layout.page-header title="Esplora il Blog PIMEL"
            subtitle="Trova articoli, spunti e risorse sulla pedagogia per ogni fase della crescita.">

            {{-- Newsletter button --}}
            <div class="mt-3 mb-3">
                <a href="{{ route('newsletter.form') }}" class="btn btn-ctc btn-lg">
                    <i class="bi bi-envelope me-2"></i>
                    Iscriviti alla Newsletter
                </a>
            </div>
        </x-layout.page-header>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-11">
                <div class ="border-bottom border-dark py-0 my-0"
                <section>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4"> {{-- Modificato gap e justify-content-center --}}
                        {{-- Bottoni Rubriche (input + label) --}}
                        <input type="radio" class="btn-check" name="rubric_filter" id="rubric_all" value=""
                            autocomplete="off" {{ !$selectedRubricSlug ? 'checked' : '' }}>
                        <label class="btn btn-outline-dark rounded-pill px-3 py-2" for="rubric_all">
                            {{-- Aggiunto padding --}}
                            <span class="material-symbols-outlined fs-6 me-1 align-middle">select_all</span>
                            Tutte le rubriche
                        </label>

                        @foreach ($rubrics as $rubric)
                            <input type="radio" class="btn-check" name="rubric_filter" id="rubric_{{ $rubric->slug }}"
                                value="{{ $rubric->slug }}" autocomplete="off"
                                {{ $selectedRubricSlug == $rubric->slug ? 'checked' : '' }}>
                            <label class="btn btn-outline-dark rounded-pill px-3 py-2" {{-- Aggiunto padding --}}
                                for="rubric_{{ $rubric->slug }}">{{ $rubric->name }}</label>
                        @endforeach
                    </div>
                </section>
                </div>  
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-7">
                <div class="mb-4 mt-4">
                    <ul class="nav nav-underline justify-content-start" id="sortTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $sortBy === 'published_at_desc' ? 'active' : '' }}"
                                href="{{ route('blog.index', ['ordina_per' => 'published_at_desc', 'rubrica' => $selectedRubricSlug]) }}">
                                Ultimi articoli
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $sortBy === 'published_at_asc' ? 'active' : '' }}"
                                href="{{ route('blog.index', ['ordina_per' => 'published_at_asc', 'rubrica' => $selectedRubricSlug]) }}">
                                Meno recenti
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $sortBy === 'likes_desc' ? 'active' : '' }}"
                                href="{{ route('blog.index', ['ordina_per' => 'likes_desc', 'rubrica' => $selectedRubricSlug]) }}">
                                Più piaciuti
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $sortBy === 'comments_desc' ? 'active' : '' }}"
                                href="{{ route('blog.index', ['ordina_per' => 'comments_desc', 'rubrica' => $selectedRubricSlug]) }}">
                                Più commentati
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Lista articoli a colonna singola --}}
                <div id="article-list">
                    <div class="list-group list-group-flush list-group-item-action active mb-3">

                        {{-- Ciclo sugli articoli --}}
                        @foreach ($articles as $article)
                            <x-shared.article-card :article="$article" />
                        @endforeach
                    </div>
                </div>

                {{-- Paginazione centrata --}}
                @if ($articles->hasPages())
                    <div class="d-flex justify-content-start mt-5 mb-4">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rubricFilterRadios = document.querySelectorAll('input[name="rubric_filter"]');
                const baseUrl = '{{ route('blog.index') }}';

                rubricFilterRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.checked) {
                            const selectedRubricValue = this.value;
                            let newUrl = baseUrl;

                            if (selectedRubricValue) {
                                newUrl += '?rubrica=' + encodeURIComponent(selectedRubricValue);
                            }

                            window.location.href = newUrl;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
