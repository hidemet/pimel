@props(['article'])

{{-- CARD HORIZONTAL BOOTSTRAP NATIVA --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="row g-0 justify-content-center">
        {{-- COLONNA IMMAGINE --}}
        <div class="col-6 col-sm-4 col-md-2">
            <div>
                <a href="{{ route('blog.show', $article->slug) }}">
                    <img src="{{ $article->image_url ?? asset('assets/img/placeholder_articolo.png') }}"
                        alt="{{ $article->title }}" class="img-fluid w-100 h-100 object-fit-cover p-2">
                </a>
            </div>
        </div>

        {{-- COLONNA CONTENUTO --}}
        <div class="col-sm-8 col-md-10">
            {{-- Badge Rubrica (se presente) --}}
            <div class="card-body d-flex flex-column h-100">
                {{-- Badge Rubriche --}}
                @if ($article->rubrics->isNotEmpty())
                    <div class="mb-2">
                        @foreach ($article->rubrics->take(2) as $rubric)
                            <a href="{{ route('blog.index', ['rubrica' => $rubric->slug]) }}"
                                class="badge bg-primary-subtle text-primary-emphasis text-decoration-none me-1 rounded-pill">
                                {{ $rubric->name }}
                            </a>
                        @endforeach
                        @if ($article->rubrics->count() > 2)
                            <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">
                                +{{ $article->rubrics->count() - 2 }}
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Titolo --}}
                <h3 class="card-title h5 fw-bold mb-2">
                    <a href="{{ route('blog.show', $article->slug) }}"
                        class="text-decoration-none text-body stretched-link">
                        {{ Str::limit($article->title, 100) }}
                    </a>
                </h3>

                {{-- Excerpt --}}
                <p class="card-text text-muted mb-3 flex-grow-1">
                    {{ Str::limit($article->excerpt, 150) }}
                </p>

                {{-- Footer con meta info --}}
                <div class="card-text">
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <div>
                            @if ($article->published_at)
                                <span class="me-3">
                                    <i class="bi bi-calendar3 me-1"></i>
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
                            <span>
                                <i class="bi bi-clock me-1"></i>
                                {{ $article->reading_time ?? 'N/D' }} min
                            </span>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <i class="bi bi-heart me-1"></i>
                                {{ $article->likes_count ?? $article->likes->count() }}
                            </span>
                            <span>
                                <i class="bi bi-chat me-1"></i>
                                {{ $article->comments_count ?? $article->comments->where('is_approved', true)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
