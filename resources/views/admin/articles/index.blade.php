{{-- resources/views/admin/articles/index.blade.php --}}
<x-app-layout :bodyClass="'bg-body-private-default'" :useAdminNavigation="true">
    @section('title', 'Gestione Articoli - Admin PIMEL')

    <x-slot name="pageHeader">
        <x-layout.page-header title="Gestione Articoli">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <span class="material-symbols-outlined fs-6 me-1 align-middle">add_circle</span>
                Nuovo Articolo
            </a>
        </x-layout.page-header>
    </x-slot>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Riga 1: Segmented Buttons per Stato --}}
        <div class="nav nav-pills-segmented mb-3">
            @foreach ($articleDisplayStatuses as $statusKey => $statusData)
                <a href="{{ route('admin.articles.index', array_merge(request()->except(['status', 'page']), ['status' => $statusKey])) }}"
                    class="nav-link {{ $currentStatusFilter == $statusKey ? 'active' : '' }} d-flex align-items-center gap-1 flex-wrap justify-content-center px-2 py-1">
                    <span class="material-symbols-outlined fs-6">{{ $statusData['icon'] }}</span>
                    <span class="small">{{ $statusData['text'] }}</span>
                    <span
                        class="badge rounded-pill {{ $currentStatusFilter == $statusKey ? 'bg-white text-primary' : 'bg-secondary bg-opacity-25' }} ms-1">
                        {{ $statusData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Riga 2: Ricerca, Filtri Dropdown, Ordinamento Dropdown --}}
        <div class="mb-4">
            <form id="mainFilterSortForm" action="{{ route('admin.articles.index') }}" method="GET"
                class="row g-2 align-items-center">
                {{-- Input nascosti per mantenere lo stato e l'ordinamento quando si invia solo la ricerca testuale --}}
                <input type="hidden" name="status" value="{{ $currentStatusFilter }}">
                <input type="hidden" name="rubric_id" value="{{ request('rubric_id', 'all') }}">
                <input type="hidden" name="author_id" value="{{ request('author_id', 'all') }}">
                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'published_at') }}">
                <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">

                {{-- Colonna Ricerca Testuale --}}
                <div class="col-md-5 col-lg-6">
                    <label for="search_term_main" class="visually-hidden">Cerca Articoli</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><span class="material-symbols-outlined fs-6">search</span></span>
                        <input type="text" name="search" id="search_term_main" class="form-control"
                            placeholder="Cerca titolo, autore..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Colonna Filtri e Ordinamento --}}
                <div class="col-md-7 col-lg-6 d-flex justify-content-md-end align-items-center gap-2">
                    {{-- Pulsante Filtri Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center" type="button"
                            id="filterDropdownButton" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-auto-close="outside">
                            <span class="material-symbols-outlined fs-6 me-1">filter_alt</span>
                            Filtri
                            @if ($activeFilterCount > 0)
                                <span class="badge rounded-pill bg-primary ms-2">{{ $activeFilterCount }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0"
                            aria-labelledby="filterDropdownButton" style="min-width: 320px;">
                            <h6 class="dropdown-header px-0 mb-2">Filtri Avanzati</h6>
                            {{-- Form interno al dropdown per applicare filtri specifici --}}
                            <form id="advancedFilterDropdownForm" action="{{ route('admin.articles.index') }}"
                                method="GET">
                                {{-- Mantiene lo stato corrente e l'ordinamento --}}
                                <input type="hidden" name="status" value="{{ $currentStatusFilter }}">
                                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'published_at') }}">
                                <input type="hidden" name="sort_direction"
                                    value="{{ request('sort_direction', 'desc') }}">
                                {{-- Mantiene la ricerca testuale principale se presente --}}
                                <input type="hidden" name="search" value="{{ request('search') }}">


                                <div class="mb-3">
                                    <label for="dd_date_from" class="form-label form-label-sm">Data Pubblicazione
                                        Da</label>
                                    <input type="date" name="date_from" id="dd_date_from"
                                        class="form-control form-control-sm" value="{{ request('date_from') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="dd_date_to" class="form-label form-label-sm">Data Pubblicazione
                                        A</label>
                                    <input type="date" name="date_to" id="dd_date_to"
                                        class="form-control form-control-sm" value="{{ request('date_to') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="dd_rubric_id" class="form-label form-label-sm">Rubrica</label>
                                    <select name="rubric_id" id="dd_rubric_id" class="form-select form-select-sm">
                                        <option value="all">Tutte le Rubriche</option>
                                        @foreach ($allRubrics as $rubric)
                                            <option value="{{ $rubric->id }}"
                                                {{ request('rubric_id') == $rubric->id ? 'selected' : '' }}>
                                                {{ $rubric->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="dd_author_id" class="form-label form-label-sm">Autore</label>
                                    <select name="author_id" id="dd_author_id" class="form-select form-select-sm">
                                        <option value="all">Tutti gli Autori</option>
                                        @foreach ($allAuthors as $author)
                                            <option value="{{ $author->id }}"
                                                {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.articles.index', ['status' => $currentStatusFilter, 'sort_by' => request('sort_by', 'published_at'), 'sort_direction' => request('sort_direction', 'desc'), 'search' => request('search')]) }}"
                                        class="btn btn-sm btn-outline-secondary">Cancella Filtri</a>
                                    <button type="submit" class="btn btn-sm btn-primary">Applica</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Pulsante Ordinamento Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center"
                            type="button" id="sortDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined fs-6 me-1">sort</span>
                            {{ $sortOptions[$currentSortKey] ?? 'Ordina per...' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                            aria-labelledby="sortDropdownButton">
                            @foreach ($sortOptions as $key => $label)
                                @php
                                    [$sortByOption, $sortDirOption] = explode('_', $key, 2);
                                    if (str_ends_with($sortByOption, 'count')) {
                                        // es. likes_count_desc
                                        $sortByOption = $sortByOption; // Il nome del campo è già 'likes_count'
                                        // $sortDirOption rimane 'desc' o 'asc'
                                    } elseif (str_ends_with($key, '_desc') || str_ends_with($key, '_asc')) {
                                        $sortDirOption = Str::afterLast($key, '_');
                                        $sortByOption = Str::beforeLast($key, '_' . $sortDirOption);
                                    } else {
                                        // fallback o errore di logica se la chiave non è formattata come previsto
                                        $sortDirOption = 'desc';
                                    }
                                @endphp
                                <li>
                                    <a class="dropdown-item {{ $currentSortKey == $key ? 'active' : '' }}"
                                        href="{{ route('admin.articles.index', array_merge(request()->all(), ['sort_by' => $sortByOption, 'sort_direction' => $sortDirOption, 'page' => 1])) }}">
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- Pulsante di submit per la ricerca testuale (se si vuole premere invio) --}}
                {{-- Può essere rimosso se si implementa la ricerca live --}}
                <button type="submit" class="visually-hidden" aria-hidden="true">Invia Ricerca</button>
            </form>
        </div>


        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 35%;">Titolo</th>
                                <th scope="col" style="width: 15%;">Autore</th> {{-- REINTRODOTTO Autore per il filtro --}}
                                <th scope="col" style="width: 15%;">Rubriche</th>
                                <th scope="col" class="text-center" style="width: 5%;"><span
                                        class="material-symbols-outlined fs-6" title="Commenti">chat_bubble</span>
                                </th>
                                <th scope="col" class="text-center" style="width: 5%;"><span
                                        class="material-symbols-outlined fs-6" title="Mi Piace">thumb_up</span></th>
                                <th scope="col" style="width: 12%;">Stato</th>
                                <th scope="col" style="width: 13%;">Pubblicato il</th>
                                <th scope="col" class="text-end" style="width: 10%;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($articles as $article)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                            class="fw-medium text-decoration-none"
                                            title="Modifica: {{ $article->title }}">
                                            {{ Str::limit($article->title, 60) }}
                                        </a>
                                        @if ($article->status === 'published' || $article->status === 'scheduled')
                                            <a href="{{ route('blog.show', $article->slug) }}" target="_blank"
                                                class="ms-1 text-muted" title="Visualizza articolo sul sito">
                                                <span class="material-symbols-outlined fs-6"
                                                    style="vertical-align: text-bottom;">open_in_new</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $article->author->name ?? 'N/A' }}</td> {{-- REINTRODOTTO Autore --}}
                                    <td>
                                        @foreach ($article->rubrics->take(2) as $rubric)
                                            <span
                                                class="badge bg-light border text-dark fw-normal me-1 mb-1">{{ $rubric->name }}</span>
                                        @endforeach
                                        @if ($article->rubrics->count() > 2)
                                            <span
                                                class="badge bg-light border text-dark fw-normal mb-1">+{{ $article->rubrics->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $article->comments_count ?? 0 }}</td>
                                    <td class="text-center">{{ $article->likes_count ?? 0 }}</td>
                                    <td>
                                        @php
                                            $statusKeyForDisplay = $article->status ?? 'default';
                                            $statusConfig = $articleDisplayStatuses[$statusKeyForDisplay] ?? [
                                                'class' => 'bg-secondary',
                                                'text' => Str::ucfirst($statusKeyForDisplay),
                                                'icon' => 'label_important',
                                            ];
                                            $badgeClass = $statusConfig['class'] ?? 'bg-secondary';
                                            if (
                                                $statusKeyForDisplay === 'draft' ||
                                                $statusKeyForDisplay === 'scheduled'
                                            ) {
                                                // Esempio per rendere il testo scuro su sfondi chiari
                                                $badgeClass .=
                                                    strpos($badgeClass, 'text-dark') === false ? ' text-dark' : '';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} d-inline-flex align-items-center">
                                            <span
                                                class="material-symbols-outlined fs-6 me-1">{{ $statusConfig['icon'] }}</span>
                                            {{ $statusConfig['text'] }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $article->published_at ? $article->published_at->isoFormat('D MMM \'YY, HH:mm') : ($article->status === 'scheduled' && $article->published_at ? $article->published_at->isoFormat('D MMM \'YY, HH:mm') . ' (P)' : 'Non definito') }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                            class="btn btn-sm btn-outline-primary py-1 px-2 me-1" title="Modifica">
                                            <span class="material-symbols-outlined fs-6 align-middle">edit</span>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Sei sicuro di voler eliminare questo articolo «{{ addslashes($article->title) }}»? L\'azione è irreversibile.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2"
                                                title="Elimina">
                                                <span class="material-symbols-outlined fs-6 align-middle">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5"> {{-- Aggiornato colspan --}}
                                        <span class="material-symbols-outlined fs-1 text-muted mb-2">search_off</span>
                                        <p class="mb-0">Nessun articolo trovato corrispondente ai criteri di ricerca.
                                        </p>
                                        @if (request()->except('page', 'status', 'sort_by', 'sort_direction'))
                                            <p class="mt-2"><a
                                                    href="{{ route('admin.articles.index', ['status' => $currentStatusFilter, 'sort_by' => request('sort_by', 'published_at'), 'sort_direction' => request('sort_direction', 'desc')]) }}"
                                                    class="btn btn-sm btn-outline-secondary">Rimuovi filtri
                                                    avanzati</a></p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($articles->hasPages())
                <div class="card-footer bg-light border-top-0">
                    {{ $articles->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gestione invio form principale per ricerca testuale
                const mainFilterSortForm = document.getElementById('mainFilterSortForm');
                const searchInputMain = document.getElementById('search_term_main');

                if (searchInputMain && mainFilterSortForm) {
                    searchInputMain.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            event
                        .preventDefault(); // Previene l'invio standard del form più esterno se ce n'è uno
                            // Trova tutti i campi del form dropdown e imposta i loro valori
                            // nel form principale prima di inviarlo, o assicurati che il form principale
                            // sia l'unico inviato.
                            // Per semplicità, qui presumiamo che i campi nascosti siano già aggiornati
                            // o che il submit del form più esterno sia sufficiente.
                            mainFilterSortForm.submit();
                        }
                    });
                }

                //  (Ricerca Live commentata, da implementare se desiderato con AJAX)
                // let debounceTimeout;
                // if(searchInputMain) {
                //     searchInputMain.addEventListener('input', function () {
                //         clearTimeout(debounceTimeout);
                //         debounceTimeout = setTimeout(() => {
                //             mainFilterSortForm.submit(); // Semplice ricaricamento pagina
                //         }, 700);
                //     });
                // }
            });
        </script>
    @endpush
</x-app-layout>
