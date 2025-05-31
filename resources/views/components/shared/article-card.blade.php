{{-- resources/views/components/shared/article-card.blade.php --}}
@props(['article'])

<div class="list-group-item py-3 px-3 bg-transparent">
    <article>
        <div class="row g-3 align-items-start">

            {{-- COLONNA TESTO (Rubriche, Titolo, Excerpt) --}}
            <div class="col flex-shrink-1"> {{-- flex-shrink-1 è l'equivalente Bootstrap di min-width: 0 --}}
                <header class="mb-2"> {{-- Header per rubriche e titolo --}}
                    @if ($article->rubrics->isNotEmpty())
                        <div class="mb-1">
                            @foreach ($article->rubrics->take(2) as $rubric)
                                <a href="{{ route('blog.index', ['rubrica' => $rubric->slug]) }}"
                                    class="text-decoration-none text-dark me-2 small fw-medium">
                                    {{ $rubric->name }}
                                </a>
                            @endforeach
                            @if ($article->rubrics->count() > 2)
                                <span class="text-muted small">
                                    +{{ $article->rubrics->count() - 2 }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <h3 class="h5 mb-2 lh-tight article-title-clamp"> {{-- Classe per troncamento titolo --}}
                        <a href="{{ route('blog.show', $article->slug) }}" class="text-decoration-none text-body">
                            {{ $article->title }}
                        </a>
                    </h3>
                </header>

                <p class="text-muted small mb-0 lh-sm excerpt-clamp-custom">
                    {{ Str::limit(strip_tags($article->excerpt ?? ''), 150) }}
                </p>
            </div>

            {{-- COLONNA IMMAGINE --}}
            <div class="col-5 col-md-4 col-lg-3 flex-shrink-0">
                <a href="{{ route('blog.show', $article->slug) }}"
                    class="d-block text-decoration-none article-image-link">
                    <div class="ratio ratio-1x1 overflow-hidden rounded">
                        @php
                            $imageUrl =
                                $article->image_url ??
                                'https://picsum.photos/seed/' . ($article->id ?? rand(1, 1000)) . '/200/200';
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Anteprima dell'articolo: {{ $article->title }}"
                            loading="lazy" class="object-fit-cover w-100 h-100">
                    </div>
                </a>
            </div>
        </div>

        {{-- RIGA SEPARATA PER I METADATI (FOOTER) --}}
        <footer class="article-meta d-flex align-items-center small text-muted w-100 mt-2 pt-2">
            <div class="flex-grow-1">
                @if ($article->published_at)
                    <span title="{{ $article->published_at->format('d F Y H:i') }}">
                        @if ($article->published_at->isToday())
                            Oggi
                        @elseif($article->published_at->isYesterday())
                            Ieri
                        @elseif($article->published_at->diffInDays(now()) < 7)
                            {{ $article->published_at->diffForHumans(null, true) }} fa
                        @else
                            {{ $article->published_at->format('d M Y') }}
                        @endif
                    </span>
                @endif
                @if ($article->published_at && ($article->reading_time ?? false))
                    <span class="mx-1">•</span>
                @endif
                @if ($article->reading_time ?? false)
                    <span>
                        {{ $article->reading_time }} min lettura
                    </span>
                @endif
            </div>

            <div class="d-flex align-items-center flex-shrink-0 ms-auto">
                {{-- INIZIO MODIFICA PER STATO INIZIALE "MI PIACE" --}}
                @php
                    // Determina se l'utente corrente ha messo "Mi Piace", basandosi sulla proprietà
                    // $article->has_liked preparata nel controller.
                    $hasLiked = Auth::check() && isset($article->has_liked) ? (bool) $article->has_liked : false;
                @endphp

                <button type="button"
                    class="btn btn-sm btn-link p-0 me-3 like-button text-decoration-none {{ $hasLiked ? 'text-primary' : 'text-muted' }}"
                    onclick="toggleLike(this, {{ $article->id }})">
                    <i class="bi {{ $hasLiked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-1"></i>
                    <span class="like-count">{{ $article->likes_count ?? 0 }}</span> {{-- Modificato da link-count a like-count --}}
                </button>
                {{-- FINE MODIFICA PER STATO INIZIALE "MI PIACE" --}}

                <a href="{{ route('blog.show', $article->slug) }}#comments"
                    class="btn btn-sm btn-link text-muted text-decoration-none p-0">
                    <i class="bi bi-chat me-1"></i>
                    <span class="comment-count">{{ $article->comments_count ?? 0 }}</span> {{-- Modificato da commnet-count a comment-count --}}
                </a>
            </div>
        </footer>
    </article>
</div>

{{-- Script toggleLike --}}
@once
    @push('scripts')
        <script>
            async function toggleLike(button, articleId) {
                @auth
                const icon = button.querySelector('i');
                const countSpan = button.querySelector('.like-count'); // Assicurati che la classe sia 'like-count'
                const url = `/articles/${articleId}/like`;
                let dataForIconRestore; // Variabile per memorizzare lo stato del like per il ripristino dell'icona

                button.disabled = true;
                // Rimuovi le classi dell'icona esistente e aggiungi lo spinner
                const originalIconClasses = Array.from(icon.classList).filter(cls => cls.startsWith('bi-'));
                icon.classList.remove(...originalIconClasses);
                icon.classList.add('spinner-border', 'spinner-border-sm');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                    });

                    if (!response.ok) {
                        console.error('Errore HTTP:', response.status, response.statusText);
                        // Ripristina le classi originali dell'icona in caso di errore
                        icon.classList.remove('spinner-border', 'spinner-border-sm');
                        icon.classList.add(...originalIconClasses);
                        button.disabled = false;
                        return;
                    }

                    const data = await response.json();
                    dataForIconRestore = data; // Salva i dati per il blocco finally

                    // Aggiorna lo stato del bottone e il conteggio
                    if (data.liked) {
                        // Icona e testo verranno aggiornati nel blocco finally
                        button.classList.remove('text-muted');
                        button.classList.add('text-primary');
                    } else {
                        button.classList.remove('text-primary');
                        button.classList.add('text-muted');
                    }
                    countSpan.textContent = data.likes_count;

                } catch (error) {
                    console.error('Errore nella richiesta toggleLike:', error);
                    // Ripristina le classi originali dell'icona in caso di errore di rete
                    icon.classList.remove('spinner-border', 'spinner-border-sm');
                    icon.classList.add(...originalIconClasses);
                } finally {
                    // Riabilita il bottone e ripristina l'icona corretta
                    button.disabled = false;
                    icon.classList.remove('spinner-border', 'spinner-border-sm');
                    if (dataForIconRestore) { // Assicurati che dataForIconRestore sia definito
                        icon.classList.add(dataForIconRestore.liked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up');
                    } else {
                        // Fallback se dataForIconRestore non è definito (es. errore prima della risposta json)
                        // Questo ripristina l'icona allo stato in cui era PRIMA del click,
                        // che potrebbe non essere l'ideale ma è un fallback.
                        // Idealmente, lo stato dovrebbe essere ricaricato o non cambiato se c'è un errore grave.
                        // Per ora, ci affidiamo al fatto che dataForIconRestore sarà quasi sempre definito.
                        icon.classList.add(...
                        originalIconClasses); // Ripristina le classi iniziali se data non è disponibile
                    }
                }
            @else
                const loginUrl = new URL('{{ route('login') }}');
                loginUrl.searchParams.append('redirect_reason', 'like_article');
                window.location.href = loginUrl.toString();
            @endauth
            }
        </script>
    @endpush
@endonce
