{{-- resources/views/blog/index.blade.php --}}
<x-app-layout>
    @section('title', $selectedRubricSlug ? 'Articoli per: ' . Str::title(str_replace('-', ' ', $selectedRubricSlug)) .
        ' - Blog' : 'Blog - Pedagogia In Movimento')
    @section('description',
        'Esplora tutti gli articoli del blog di Pedagogia In Movimento. Approfondimenti, consigli e
        risorse su genitorialità, sviluppo infantile, adolescenza, scuola e professione pedagogica.')
        <div class="container page-content-container">

            <x-layout.page-header title="Esplora il Blog PIMEL"
                subtitle="Trova articoli, spunti e risorse sulla pedagogia per ogni fase della crescita."
                bgClass="blog-header-bg">
            </x-layout.page-header>

            <!-- Sezione Filtri Rubriche (Radio Button Group) -->
            @if ($rubrics->count())
                <section class="container mt-4 mb-4">
                    <div class="row">
                        <div class="col-12"> {{-- Assicurati che occupi tutta la larghezza per il justify-content --}}
                            <div id="rubric-filter-group" class="btn-group flex-wrap  rubric-filter-buttons">
                                {{-- Opzione "Tutte le Rubriche" --}}


                                <input type="radio" class="btn-check" name="rubric_filter" id="rubric_all" value=""
                                    autocomplete="off" {{ !$selectedRubricSlug ? 'checked' : '' }}>
                                <label class="btn m-1 rounded-3 px-3 py-2" for="rubric_all">
                                    <span class="material-symbols-outlined fs-6 me-1">select_all</span>
                                    Tutte le rubriche
                                </label>

                                {{-- Loop per le rubriche --}}
                                @foreach ($rubrics as $rubric)
                                    <input type="radio" class="btn-check" name="rubric_filter"
                                        id="rubric_{{ $rubric->slug }}" value="{{ $rubric->slug }}" autocomplete="off"
                                        {{ $selectedRubricSlug == $rubric->slug ? 'checked' : '' }}>
                                    <label class="btn m-1 rounded-3 px-3 py-2"
                                        for="rubric_{{ $rubric->slug }}">{{ $rubric->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <!-- Layout con Articoli e Sidebar -->
            <section class="container">
                <x-layout.two-column-sidebar>
                    {{-- Questo contenuto andrà nello $slot della colonna principale --}}
                    <div>
                        <!-- Tab per Ordinamento Articoli (rimane invariato) -->
                        <ul class="nav nav-underline mb-4 justify-content-start" id="sortTabs" role="tablist">
                            {{-- ... i tuoi link di ordinamento ... --}}
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $sortBy === 'published_at_desc' ? 'active' : '' }}"
                                    href="{{ route('blog.index', array_merge(request()->query(), ['ordina_per' => 'published_at_desc', 'rubrica' => $selectedRubricSlug])) }}">
                                    Ultimi articoli
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $sortBy === 'published_at_asc' ? 'active' : '' }}"
                                    href="{{ route('blog.index', array_merge(request()->query(), ['ordina_per' => 'published_at_asc', 'rubrica' => $selectedRubricSlug])) }}">
                                    Meno recenti
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $sortBy === 'likes_desc' ? 'active' : '' }}"
                                    href="{{ route('blog.index', array_merge(request()->query(), ['ordina_per' => 'likes_desc', 'rubrica' => $selectedRubricSlug])) }}">
                                    Più piaciuti
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $sortBy === 'comments_desc' ? 'active' : '' }}"
                                    href="{{ route('blog.index', array_merge(request()->query(), ['ordina_per' => 'comments_desc', 'rubrica' => $selectedRubricSlug])) }}">
                                    Più commentati
                                </a>
                            </li>
                        </ul>

                        <div id="article-list">
                            <x-shared.article-list :articles="$articles" />
                        </div>

                        <!-- Paginazione -->
                        @if ($articles->hasPages())
                            <div class="mt-4 mb-4">
                                {{ $articles->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </x-layout.two-column-sidebar>
            </section>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const rubricFilterRadios = document.querySelectorAll('input[name="rubric_filter"]');
                        const baseUrl = "{{ route('blog.index') }}";
                        const currentParams = new URLSearchParams(window.location.search);
                        const sortBy = currentParams.get('ordina_per') ||
                            'published_at_desc'; // Mantieni l'ordinamento corrente o usa il default

                        rubricFilterRadios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                if (this.checked) {
                                    const selectedRubricValue = this.value;
                                    let newUrl = baseUrl + '?ordina_per=' + encodeURIComponent(sortBy);

                                    if (selectedRubricValue) {
                                        newUrl += '&rubrica=' + encodeURIComponent(selectedRubricValue);
                                    }
                                    // Se selectedRubricValue è vuoto (Tutte le Rubriche), non aggiungiamo il parametro 'rubrica'
                                    // e l'URL rimarrà /blog?ordina_per=... che mostrerà tutti gli articoli

                                    window.location.href = newUrl;
                                }
                            });
                        });

                        // AGGIORNAMENTO: Assicurarsi che anche i link di ordinamento mantengano la rubrica selezionata (se presente)
                        // Questo viene già gestito passando 'rubrica' => $selectedRubricSlug in array_merge nei link di ordinamento.
                        // Quindi lo script per i radio è l'aggiunta principale.
                    });
                </script>
            @endpush
        </div>
    </x-app-layout>
