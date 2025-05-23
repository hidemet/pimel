{{-- resources/views/blog/show.blade.php --}}
<x-app-layout>
    @section('title', $article->title . ' - Blog PIMEL')
    @section('description', $article->meta_description ?? Str::limit(strip_tags($article->excerpt), 160))

    @push('meta')
        <meta property="og:title" content="{{ $article->title }}" />
        <meta property="og:description"
            content="{{ $article->meta_description ?? Str::limit(strip_tags($article->excerpt), 160) }}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{ route('blog.show', $article->slug) }}" />
        @if ($article->image_url)
            <meta property="og:image" content="{{ $article->image_url }}" />
        @endif
        <meta property="article:published_time" content="{{ $article->published_at?->toIso8601String() }}" />
        <meta property="article:author" content="{{ $article->author->name }}" />
        @if ($article->rubrics->isNotEmpty())
            @foreach ($article->rubrics as $rubric)
                <meta property="article:section" content="{{ $rubric->name }}" />
            @endforeach
        @endif
    @endpush

    <div class="container-fluid px-0">
        <div class="container py-4">
            <!-- Progress bar per il reading progress -->
            <div class="progress position-fixed top-0 start-0 w-100" style="height: 3px; z-index: 1030;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="reading-progress"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    <!-- Breadcrumb migliorato -->
                    <nav aria-label="Percorso di navigazione" class="mb-4">
                        <ol class="breadcrumb bg-light rounded-pill px-3 py-2 shadow-sm">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="bi bi-house-fill me-1"></i>Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('blog.index') }}" class="text-decoration-none">Blog</a>
                            </li>
                            @if ($article->rubrics->isNotEmpty())
                                <li class="breadcrumb-item">
                                    <a href="{{ route('blog.index', ['rubrica' => $article->rubrics->first()->slug]) }}"
                                        class="text-decoration-none">
                                        {{ $article->rubrics->first()->name }}
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active text-truncate" aria-current="page"
                                style="max-width: 200px;">
                                {{ $article->title }}
                            </li>
                        </ol>
                    </nav>

                    <!-- Header dell'articolo -->
                    <header class="mb-5">
                        <!-- Categorie/Rubriche -->
                        @if ($article->rubrics->isNotEmpty())
                            <div class="mb-3">
                                @foreach ($article->rubrics->take(3) as $rubric)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill me-2 mb-1">
                                        <a href="{{ route('blog.index', ['rubrica' => $rubric->slug]) }}"
                                            class="text-decoration-none text-primary">
                                            {{ $rubric->name }}
                                        </a>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Titolo -->
                        <h1 class="display-4 fw-bold lh-base mb-4">{{ $article->title }}</h1>

                        <!-- Card con metadati -->
                        <div class="card border-0 bg-light">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <!-- Autore e data -->
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $article->author->name }}</div>
                                                @if ($article->published_at)
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <time datetime="{{ $article->published_at->toISOString() }}">
                                                            {{ $article->published_at->format('d F Y') }}
                                                        </time>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Tempo di lettura -->
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-clock me-2"></i>
                                            <span>{{ $article->reading_time ?? 5 }} minuti di lettura</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <!-- Statistiche interazioni -->
                                        <div class="d-flex justify-content-md-end gap-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-heart-fill text-danger me-1"></i>
                                                <span class="fw-semibold">{{ $article->likes->count() }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-chat-fill text-primary me-1"></i>
                                                <span
                                                    class="fw-semibold">{{ $article->comments->where('is_approved', true)->count() }}</span>
                                            </div>
                                        </div>

                                        <!-- Pulsanti condivisione -->
                                        <div class="mt-2">
                                            <div class="btn-group" role="group" aria-label="Condividi articolo">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="shareArticle('facebook')" data-bs-toggle="tooltip"
                                                    title="Condividi su Facebook">
                                                    <i class="bi bi-facebook"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="shareArticle('twitter')" data-bs-toggle="tooltip"
                                                    title="Condividi su Twitter">
                                                    <i class="bi bi-twitter"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="copyToClipboard()" data-bs-toggle="tooltip"
                                                    title="Copia link">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Immagine principale -->
                    @if ($article->image_path)
                        <figure class="figure mb-5 w-100">
                            <img src="{{ $article->image_url }}" class="figure-img img-fluid rounded shadow"
                                alt="{{ $article->title }}" loading="lazy">
                        </figure>
                    @endif

                    <!-- Layout principale con contenuto e sidebar -->
                    <div class="row">
                        <!-- Contenuto principale -->
                        <div class="col-lg-8">
                            <!-- Indice dei contenuti -->
                            <div id="article-toc-container" class="alert alert-info border-0 mb-4"
                                style="display: none;">
                                <h5 class="alert-heading fs-6 fw-semibold mb-3">
                                    <i class="bi bi-list-ul me-2"></i>Indice dei contenuti
                                </h5>
                                <nav id="article-toc" class="small"></nav>
                                <button class="btn btn-sm btn-outline-info mt-2" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#toc-content" aria-expanded="true">
                                    <i class="bi bi-eye-slash"></i> Nascondi
                                </button>
                                <div class="collapse show" id="toc-content">
                                    <div id="toc-list"></div>
                                </div>
                            </div>

                            <!-- Corpo dell'articolo -->
                            <article class="article-content mb-5" id="article-content">
                                {!! $article->body !!}
                            </article>

                            <!-- Sezione feedback e CTA -->
                            <section class="mb-5">
                                <!-- Card feedback -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-10 text-center">
                                        <h4 class="card-title mb-0 text-primary">Ti è piaciuto questo articolo?</h4>
                                    </div>
                                    <div class="card-body text-center py-4">
                                        @auth
                                            <button class="btn btn-primary btn-lg rounded-pill px-4"
                                                onclick="likeArticle({{ $article->id }})">
                                                <i class="bi bi-hand-thumbs-up me-2"></i>
                                                Mi è piaciuto!
                                            </button>
                                        @else
                                            <div class="alert alert-light border" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <a href="{{ route('login') }}" class="alert-link">Accedi</a>
                                                per dire che ti è piaciuto questo articolo
                                            </div>
                                        @endauth
                                    </div>
                                </div>

                                <!-- Newsletter CTA -->
                                <div class="card bg-secondary bg-opacity-10 border-0">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-envelope-heart fs-1 text-secondary mb-3"></i>
                                        <h5 class="card-title">Resta sempre aggiornato!</h5>
                                        <p class="card-text text-muted">
                                            Iscriviti alla newsletter PIMEL per ricevere i nuovi articoli,
                                            risorse e novità sui servizi di consulenza.
                                        </p>
                                        <a href="{{ route('newsletter.form') }}"
                                            class="btn btn-secondary rounded-pill px-4">
                                            <i class="bi bi-envelope-plus me-2"></i>
                                            Iscriviti alla Newsletter
                                        </a>
                                    </div>
                                </div>
                            </section>

                            <!-- Box autore -->
                            @include('blog.partials._author-box', ['author' => $article->author])

                            <!-- Sezione commenti -->
                            @include('blog.partials._comments-section', ['article' => $article])
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 2rem;">
                                <!-- Navigazione rapida -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-compass me-2"></i>Navigazione rapida
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="#article-content" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-arrow-up"></i> Torna all'inizio
                                            </a>
                                            <a href="#comments" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chat"></i> Vai ai commenti
                                            </a>
                                            <a href="{{ route('blog.index') }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-left"></i> Altri articoli
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Newsletter card -->
                                <x-shared.newsletter-card />

                                <!-- Service promo card -->
                                <x-shared.service-promo-card />
                            </div>
                        </div>
                    </div>

                    <!-- Articoli correlati -->
                    @if ($relatedArticles->count())
                        <section class="mt-5 pt-5 border-top">
                            <h2 class="text-center mb-4">
                                <i class="bi bi-lightbulb me-2 text-warning"></i>
                                Altri articoli che potrebbero interessarti
                            </h2>
                            <div class="row g-4">
                                @foreach ($relatedArticles->take(3) as $related)
                                    <div class="col-md-4">
                                        <x-shared.article-card :article="$related" variant="compact" />
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Toast per feedback utente -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="feedback-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Successo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Operazione completata con successo!
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Progress bar di lettura
                const progressBar = document.getElementById('reading-progress');
                const articleContent = document.getElementById('article-content');

                function updateReadingProgress() {
                    const scrollTop = window.pageYOffset;
                    const docHeight = document.body.scrollHeight - window.innerHeight;
                    const scrollPercent = (scrollTop / docHeight) * 100;
                    progressBar.style.width = Math.min(scrollPercent, 100) + '%';
                }

                window.addEventListener('scroll', updateReadingProgress);

                // Generazione TOC
                generateTableOfContents();

                // Inizializzazione tooltip
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Smooth scroll per link interni
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            });

            // Funzione per generare l'indice dei contenuti
            function generateTableOfContents() {
                const tocContainer = document.getElementById('article-toc');
                const tocWrapper = document.getElementById('article-toc-container');
                const tocList = document.getElementById('toc-list');

                if (!tocContainer || !tocWrapper) return;

                const articleContent = document.querySelector('.article-content');
                const headings = articleContent.querySelectorAll('h2, h3, h4');

                if (headings.length < 2) {
                    tocWrapper.style.display = 'none';
                    return;
                }

                const ul = document.createElement('ul');
                ul.className = 'list-unstyled mb-0';

                headings.forEach((heading, index) => {
                    if (!heading.textContent.trim()) return;

                    let id = heading.getAttribute('id');
                    if (!id) {
                        id = 'heading-' + index;
                        heading.setAttribute('id', id);
                    }

                    const li = document.createElement('li');
                    li.className = 'mb-2';

                    if (heading.tagName === 'H3') li.classList.add('ps-3');
                    if (heading.tagName === 'H4') li.classList.add('ps-4');

                    const a = document.createElement('a');
                    a.href = '#' + id;
                    a.textContent = heading.textContent;
                    a.className = 'text-decoration-none text-body';

                    // Hover effect
                    a.addEventListener('mouseenter', () => a.classList.add('text-primary'));
                    a.addEventListener('mouseleave', () => a.classList.remove('text-primary'));

                    li.appendChild(a);
                    ul.appendChild(li);
                });

                tocList.appendChild(ul);
                tocWrapper.style.display = 'block';
            }

            // Funzioni di condivisione
            function shareArticle(platform) {
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent(document.title);

                let shareUrl = '';

                switch (platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                        break;
                }

                if (shareUrl) {
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                }
            }

            function copyToClipboard() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Link copiato negli appunti!');
                });
            }

            function likeArticle(articleId) {
                // Qui implementeresti la chiamata AJAX per il like
                showToast('Grazie per il tuo feedback!');
            }

            function showToast(message) {
                const toast = document.getElementById('feedback-toast');
                const toastBody = toast.querySelector('.toast-body');
                toastBody.textContent = message;

                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        </script>
    @endpush
</x-app-layout>
