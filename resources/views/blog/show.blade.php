{{-- resources/views/blog/show.blade.php --}}
<x-app-layout>
    @section('title', $article->title . ' - Blog PIMEL')
    @section('description', $article->meta_description ?? Str::limit(strip_tags($article->excerpt), 160))

    <div class="container-fluid px-0">
        <div class="container py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                {{-- Per eventuali errori generici --}}
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="progress position-fixed top-0 start-0 w-100" style="height: 3px; z-index: 1030;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="reading-progress"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6 col-md-11">

                    <header class="mb-5 text-center">
                        @if ($article->rubrics->isNotEmpty())
                            <div class="mb-4">
                                @foreach ($article->rubrics as $rubric)
                                    <a href="{{ route('blog.index', ['rubrica' => $rubric->slug]) }}"
                                        class="btn btn-outline-dark btn-sm rounded-pill me-2 mb-2 px-3 py-1">
                                        {{ $rubric->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <h1 class="display-6 fw-semibold lh-1 mb-4 px-5">{{ $article->title }}</h1>
                        {{-- lh-1 per titolo più compatto --}}

                        {{-- Blocco Metadati Autore e Articolo --}}
                        <div class="article-metadata-block mx-auto pt-3">
                            {{-- Riga Autore --}}
                            <div class="d-flex flex-column align-items-center text-center mb-3">
                                <div class="mb-2">
                                    @if ($article->author->isAdmin() && $article->author->email === 'jackpagoda.lll@gmail.com')
                                        {{-- RIVEDERE QUESTA CONDIZIONE --}}
                                        <img src="{{ asset('assets/img/foto-profilo.png') }}"
                                            alt="Foto profilo di {{ $article->author->name }}"
                                            class="img-fluid rounded-circle shadow-sm article-author-img">
                                    @else
                                        <span
                                            class="material-symbols-outlined text-secondary article-author-icon">account_circle</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="small text-muted">di</span>
                                    <span class="fw-medium">{{ $article->author->name }}</span>
                                </div>
                            </div>

                            {{-- Riga Metadati Articolo (Data, Tempo Lettura, Likes, Commenti) --}}
                            <div
                                class="d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center small text-muted pt-3">
                                <div class="text-center text-sm-start mb-2 mb-sm-0">
                                    @if ($article->published_at)
                                        <span title="{{ $article->published_at->translatedFormat('d F Y H:i') }}">
                                            {{ $article->published_at->translatedFormat('d M Y') }}
                                        </span>
                                    @endif
                                    @if ($article->published_at && ($article->reading_time ?? false))
                                        <span class="mx-1 d-none d-sm-inline">•</span>
                                    @endif
                                    @if ($article->reading_time ?? false)
                                        <span class="d-block d-sm-inline mt-1 mt-sm-0">
                                            {{ $article->reading_time }} min lettura
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center"
                                        title="{{ $article->likes->count() }} Mi piace">
                                        <i class="bi bi-hand-thumbs-up me-1"></i>
                                        <span class="fw-semibold">{{ $article->likes->count() }}</span>
                                        <span class="visually-hidden">Mi piace</span>
                                    </div>
                                    <div class="d-flex align-items-center"
                                        title="{{ $article->comments->where('is_approved', true)->count() }} Commenti">
                                        <i class="bi bi-chat-left me-1"></i> {{-- Cambiato in bi-chat-dots per coerenza --}}
                                        <span
                                            class="fw-semibold">{{ $article->comments->where('is_approved', true)->count() }}</span>
                                        <span class="visually-hidden">Commenti</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    @if ($article->image_path)
                        <figure class="figure mb-5 w-100">
                            <img src="{{ $article->image_url }}" class="figure-img img-fluid rounded shadow"
                                alt="{{ $article->title }}" loading="lazy">
                        </figure>
                    @endif

                    <article class="article-content mb-5" id="article-content">
                        {!! $article->body !!}
                    </article>

                    <section class="mb-5">
                        <div class="card border-primary mb-4 shadow-sm">
                            <div class="card-header bg-primary bg-opacity-10 text-center">
                                <h4 class="card-title mb-0 text-primary h5">Ti è piaciuto questo articolo?</h4>
                            </div>
                            <div class="card-body text-center py-4">
                                @auth
                                    <button class="btn btn-primary btn-lg rounded-pill px-4"
                                        onclick="likeArticle({{ $article->id }})">
                                        <i class="bi bi-hand-thumbs-up me-2"></i>
                                        Mi è piaciuto!
                                    </button>
                                @else
                                    <div class="alert alert-light border small" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <a href="{{ route('login') }}" class="alert-link">Accedi</a>
                                        per dire che ti è piaciuto questo articolo.
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </section>

                    @include('blog.partials._author-box', ['author' => $article->author])
                    @include('blog.partials._comments-section', ['article' => $article])

                    @if ($relatedArticles->count())

                        <section class="d-flex flex-column bg-content-blog py-5">
                            <div class="container">
                                <div class="row">
                                    <h2 class="text-center mb-4 h4 ">
                                        Potrebbe interessarti anche
                                    </h2>
                                    {{-- MODIFICA: Articoli correlati con list-group --}}
                                    <div class="list-group list-group-flush mb-3 related-articles-list">
                                        @foreach ($relatedArticles->take(3) as $related)
                                            {{-- Usiamo il componente article-card esistente,
                                         assumendo che sia già stilizzato per list-group-item o simile.
                                         Se article-card non è un list-group-item,
                                         dovremmo wrapparlo o creare una variante.
                                         Per ora, assumiamo che article-card si integri o che lo adatterai.
                                    --}}
                                            <x-shared.article-card :article="$related" />
                                        @endforeach
                                    </div>
                                    @if (Route::has('blog.index'))
                                        <div class="text-center mt-4">
                                            <a href="{{ route('blog.index') }}"
                                                class="btn btn-outline-primary rounded-pill">
                                                Vedi tutti gli articoli del Blog
                                                <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    @endif
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div id="feedback-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto toast-title"></strong>
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
                const progressBar = document.getElementById('reading-progress');
                const articleContentElement = document.getElementById('article-content');

                function updateReadingProgress() {
                    if (!articleContentElement) return;
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollHeight = Math.max(
                        document.body.scrollHeight, document.documentElement.scrollHeight,
                        document.body.offsetHeight, document.documentElement.offsetHeight,
                        document.body.clientHeight, document.documentElement.clientHeight
                    );
                    const windowHeight = window.innerHeight;
                    const docHeight = scrollHeight - windowHeight;
                    let scrollPercent = 0;
                    if (docHeight > 0) {
                        scrollPercent = (scrollTop / docHeight) * 100;
                    }
                    progressBar.style.width = Math.min(Math.max(0, scrollPercent), 100) + '%';
                }
                window.addEventListener('scroll', updateReadingProgress, {
                    passive: true
                });
                updateReadingProgress();

                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                            if (history.pushState) {
                                history.pushState(null, null, targetId);
                            } else {
                                location.hash = targetId;
                            }
                        }
                    });
                });
            });

            function likeArticle(articleId) {
                showToast('Grazie per il tuo feedback! (Simulazione)', 'info');
            }

            function showToast(message, type = 'info') {
                const toastEl = document.getElementById('feedback-toast');
                if (!toastEl) return;
                const toastBody = toastEl.querySelector('.toast-body');
                const toastTitleEl = toastEl.querySelector('.toast-title');
                const toastHeader = toastEl.querySelector('.toast-header');
                toastBody.textContent = message;
                toastHeader.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'text-white', 'text-dark');
                let title = 'Notifica';
                let iconHtml = '<i class="bi bi-info-circle-fill me-2"></i>';
                switch (type) {
                    case 'success':
                        title = 'Successo';
                        iconHtml = '<i class="bi bi-check-circle-fill text-success me-2"></i>';
                        break;
                    case 'error':
                        title = 'Errore';
                        iconHtml = '<i class="bi bi-x-circle-fill text-danger me-2"></i>';
                        break;
                    case 'warning':
                        title = 'Attenzione';
                        iconHtml = '<i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>';
                        break;
                    case 'info':
                    default:
                        iconHtml = '<i class="bi bi-info-circle-fill text-info me-2"></i>';
                        break;
                }
                if (toastTitleEl) {
                    toastTitleEl.innerHTML = iconHtml + title;
                } else {
                    const strongMeAuto = toastHeader.querySelector('strong.me-auto');
                    if (strongMeAuto) strongMeAuto.innerHTML = iconHtml + title;
                }
                const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
                bsToast.show();
            }
        </script>
    @endpush
</x-app-layout>
