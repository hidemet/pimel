{{-- resources/views/blog/partials/_comments-section.blade.php --}}
@props(['article'])

<div class="comments-section my-5" id="comments">
    @php
        // Conta solo i commenti approvati per il titolo principale
        $approvedParentComments = $article->comments()->where('is_approved', true)->whereNull('parent_id')->get();
        $totalVisibleComments = $approvedParentComments->count();
        foreach ($approvedParentComments as $comment) {
            // Assumendo che la relazione 'replies' nel modello Comment sia già filtrata per 'is_approved'
            // o che tu voglia contare tutte le risposte caricate (che dovrebbero essere solo quelle approvate
            // se l'eager loading è fatto correttamente nel controller).
    // Per sicurezza, filtriamo di nuovo qui se la relazione non lo fa di default.
    $totalVisibleComments += $comment->replies->where('is_approved', true)->count();
        }
    @endphp
    <h3 class="mb-4 h4">Commenti ({{ $totalVisibleComments }})</h3>

    @auth
        {{-- Form per lasciare un commento principale --}}
        <div class="card mb-4 p-3 p-md-4 rounded-4">
            <h5 class="mb-3">Lascia un commento</h5>
            <form action="{{ route('comments.store', ['article' => $article->id]) }}" method="POST" id="mainCommentForm">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control @error('body', 'commentStore') is-invalid @enderror" name="body" rows="3"
                        placeholder="Scrivi qui il tuo commento..." required>{{ old('body') }}</textarea>
                    @error('body', 'commentStore')
                        {{-- Usiamo un error bag specifico se il form principale ha errori --}}
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Pubblica commento</button>
            </form>
        </div>

        {{-- Mostra i commenti principali dell'utente in attesa di approvazione --}}
        @php
            $pendingCommentsFromUser = $article
                ->comments()
                ->where('user_id', Auth::id())
                ->where('is_approved', false)
                ->whereNull('parent_id') // Solo commenti principali
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp
        @if ($pendingCommentsFromUser->isNotEmpty())
            <div class="pending-comments-user mb-4">
                <h5 class="h6 text-muted mb-2">I tuoi commenti in attesa di approvazione:</h5>
                @foreach ($pendingCommentsFromUser as $pendingComment)
                    <div class="comment-item px-0 py-3 border-bottom" id="comment-{{ $pendingComment->id }}">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-shrink-0 me-3">
                                <span class="material-symbols-outlined text-secondary fs-1">account_circle</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">{{ $pendingComment->user->name }}
                                    <span class="badge bg-info text-dark ms-2 small">In attesa di approvazione</span>
                                </h6>
                                <small class="text-muted"
                                    title="{{ $pendingComment->created_at->isoFormat('LLLL') }}">{{ $pendingComment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="ps-comment-body">
                            <p class="mb-0 text-muted"><em>{{ nl2br(e($pendingComment->body)) }}</em></p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="alert alert-info text-center small py-2">
            <a href="{{ route('login') }}" class="fw-bold">Accedi</a> o <a href="{{ route('register') }}"
                class="fw-bold">registrati</a> per lasciare un commento.
        </div>
    @endauth

    <div class="existing-comments mt-4">
        @php
            $approvedTopLevelComments = $article
                ->comments()
                ->where('is_approved', true)
                ->whereNull('parent_id')
                ->with([
                    'user',
                    'replies' => function ($query) {
                        // Carica anche le risposte dell'utente in attesa di approvazione per visualizzarle
            // Se l'utente è loggato.
                        $query
                            ->where('is_approved', true)
                            ->orWhere(function ($subQuery) {
                                if (Auth::check()) {
                                    $subQuery->where('user_id', Auth::id())->where('is_approved', false);
                                } else {
                                    // Se non è loggato, questa condizione non aggiunge nulla
                                    $subQuery->whereRaw('1=0');
                                }
                            })
                            ->with('user')
                            ->orderBy('created_at', 'asc');
                    },
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp

        @if ($approvedTopLevelComments->isNotEmpty())
            <div>
                @foreach ($approvedTopLevelComments as $comment)
                    {{-- PASSO 4: Aggiunto ID al div del commento principale --}}
                    <div class="comment-item px-0 py-3 @if (!$loop->last) border-bottom @endif"
                        id="comment-{{ $comment->id }}">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-shrink-0 me-3">
                                <span class="material-symbols-outlined text-secondary fs-1">account_circle</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                                <small class="text-muted"
                                    title="{{ $comment->created_at->isoFormat('LLLL') }}">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="ps-comment-body">
                            <p class="mb-2">
                                {{ nl2br(e($comment->body)) }}
                            </p>

                            {{-- PASSO 1: Pulsante "Rispondi" e Form di Risposta --}}
                            @auth
                                <div class="comment-actions mt-2 mb-3">
                                    <button type="button"
                                        class="btn btn-sm btn-link text-primary p-0 text-decoration-none reply-btn"
                                        data-bs-toggle="collapse" data-bs-target="#reply-form-{{ $comment->id }}"
                                        aria-expanded="false" aria-controls="reply-form-{{ $comment->id }}">
                                        <span class="material-symbols-outlined fs-6 align-middle">reply</span> Rispondi
                                    </button>
                                </div>

                                <div class="collapse reply-form-container mb-3" id="reply-form-{{ $comment->id }}">
                                    <div class="card card-body p-3 ms-md-3 shadow-sm" style="background-color: #f8f9fa;">
                                        <form
                                            action="{{ route('comments.store_reply', ['article' => $article->id, 'parentComment' => $comment->id]) }}"
                                            method="POST">
                                            @csrf
                                            {{-- Il parent_id è gestito dalla rotta, non serve un campo nascosto qui --}}
                                            <div class="mb-2">
                                                {{-- Usiamo un nome di campo e un error bag diversi per le risposte
                                                     per evitare conflitti se entrambi i form hanno errori.
                                                     Questo richiederà di validare 'reply_body_for_comment_X' nel controller,
                                                     o una strategia diversa per gli error bags.
                                                     Per semplicità, per ora usiamo lo stesso nome 'body'
                                                     e accettiamo che gli errori di validazione per le risposte
                                                     possano apparire nel messaggio di errore generico del form principale
                                                     se Laravel non li distingue automaticamente.
                                                     Oppure, modifichiamo il controller per gestire un error bag specifico.
                                                --}}
                                                <textarea
                                                    class="form-control form-control-sm @error('body', 'commentReplyStoreErrorBag_' . $comment->id) is-invalid @enderror"
                                                    name="body" rows="2" placeholder="Scrivi la tua risposta..." required></textarea>
                                                @error('body', 'commentReplyStoreErrorBag_' . $comment->id)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Invia
                                                Risposta</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#reply-form-{{ $comment->id }}">Annulla</button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                            {{-- FINE PASSO 1 --}}

                            @php
                                // Filtra le risposte per mostrare quelle approvate
                                // O quelle dell'utente loggato in attesa
$visibleReplies = $comment->replies
    ->filter(function ($reply) {
        return $reply->is_approved ||
            (Auth::check() && $reply->user_id == Auth::id() && !$reply->is_approved);
    })
    ->sortBy('created_at');
                            @endphp

                            @if ($visibleReplies->isNotEmpty())
                                <div class="mt-2">
                                    @php
                                        $approvedRepliesCount = $comment->replies->where('is_approved', true)->count();
                                    @endphp
                                    <button
                                        class="btn btn-link btn-sm p-0 text-primary fw-semibold text-decoration-none"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#replies-collapse-{{ $comment->id }}" aria-expanded="false"
                                        {{-- ID del collapse cambiato --}} aria-controls="replies-collapse-{{ $comment->id }}">
                                        <span
                                            class="material-symbols-outlined fs-6 align-middle me-1 view-replies-icon">arrow_drop_down</span>
                                        {{ $approvedRepliesCount }}
                                        {{ Str::plural('risposta approvata', $approvedRepliesCount) }}
                                        @if (Auth::check() && $visibleReplies->where('user_id', Auth::id())->where('is_approved', false)->count() > 0)
                                            (+
                                            {{ $visibleReplies->where('user_id', Auth::id())->where('is_approved', false)->count() }}
                                            in attesa)
                                        @endif
                                    </button>
                                </div>

                                <div class="collapse replies-container mt-2" id="replies-collapse-{{ $comment->id }}">
                                    {{-- ID del collapse cambiato --}}
                                    <div class="ps-replies-indent">
                                        @foreach ($visibleReplies as $reply)
                                            <div class="comment-reply mb-3 pt-2 @if (!$loop->last) border-bottom border-opacity-25 @endif"
                                                id="comment-{{ $reply->id }}">
                                                <div class="d-flex align-items-start mb-1">
                                                    <div class="flex-shrink-0 me-2">
                                                        <span
                                                            class="material-symbols-outlined text-secondary fs-2">account_circle</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 fw-semibold small">{{ $reply->user->name }}
                                                            @if (!$reply->is_approved && Auth::check() && $reply->user_id == Auth::id())
                                                                <span class="badge bg-info text-dark ms-1 small">In
                                                                    attesa</span>
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted"
                                                            title="{{ $reply->created_at->isoFormat('LLLL') }}">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                <div class="ps-reply-body">
                                                    <p
                                                        class="mb-0 small @if (!$reply->is_approved) text-muted fst-italic @endif">
                                                        {{ nl2br(e($reply->body)) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted border rounded-3 bg-light">
                <span class="material-symbols-outlined fs-2 mb-2 d-block">chat_bubble_outline</span>
                Nessun commento approvato per questo articolo.
                @guest
                    <br>Sii il primo a lasciare il tuo pensiero (dopo aver effettuato l'accesso)!
                @endguest
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script per toggle icone collapse risposte (view-replies-icon)
            const viewRepliesToggleButtons = document.querySelectorAll(
                '.replies-container + button[data-bs-toggle="collapse"], button[data-bs-target^="#replies-collapse-"]'
            ); // Selettore più specifico
            viewRepliesToggleButtons.forEach(button => {
                const targetId = button.getAttribute('data-bs-target');
                if (!targetId) return;

                const targetCollapseElement = document.querySelector(targetId);
                const icon = button.querySelector('.view-replies-icon');

                if (targetCollapseElement && icon) {
                    const updateIcon = () => {
                        if (targetCollapseElement.classList.contains('show')) {
                            icon.textContent = 'arrow_drop_up';
                        } else {
                            icon.textContent = 'arrow_drop_down';
                        }
                    };
                    updateIcon(); // Set initial state
                    targetCollapseElement.addEventListener('show.bs.collapse', updateIcon);
                    targetCollapseElement.addEventListener('hide.bs.collapse', updateIcon);
                }
            });

            // Script per gestire il focus e gli error bags per i form di risposta (opzionale avanzato)
            // Se ci sono errori di validazione per una risposta specifica, potremmo voler
            // riaprire il form di risposta corrispondente.
            // Questo richiede che il controller per storeReply invii l'errore
            // con un error bag specifico (es. 'commentReplyStoreErrorBag_COMMENT_ID')
            // Esempio di come potresti gestirlo (richiede modifiche al controller per l'error bag):
            @if ($errors->any())
                let firstErrorProcessed = false;
                @foreach ($errors->getBags() as $bagName => $errorBag)
                    @if (Str::startsWith($bagName, 'commentReplyStoreErrorBag_') && !$errorBag->isEmpty() && !$firstErrorProcessed)
                        @php
                            $commentIdForError = Str::after($bagName, 'commentReplyStoreErrorBag_');
                            $firstErrorProcessed = true; // Processa solo il primo errore per evitare scroll multipli
                        @endphp
                        const errorReplyFormId = '#reply-form-{{ $commentIdForError }}';
                        const errorReplyForm = document.querySelector(errorReplyFormId);
                        if (errorReplyForm && !errorReplyForm.classList.contains('show')) {
                            new bootstrap.Collapse(errorReplyForm).show();
                            // Scroll al form di risposta con errore, se necessario
                            // errorReplyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        @break

                        {{-- Esci dal loop dopo aver gestito il primo error bag di risposta --}}
                    @endif
                @endforeach
            @endif
        });
    </script>
@endpush
