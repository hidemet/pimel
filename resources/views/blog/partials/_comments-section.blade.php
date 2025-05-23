@props(['article'])
<div class="comments-section my-5">
    <h3 class="mb-4 h4">Commenti ({{ $article->comments->where('is_approved', true)->count() }})</h3>
    @auth
        {{-- Form per nuovo commento --}}
        <div class="card mb-4 p-3 p-md-4 rounded-4 shadow-sm">
            <h5 class="mb-3">Lascia un commento</h5>
            <form action="{{-- route('comments.store', $article->id) --}}" method="POST">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">
                <div class="mb-3">
                    <textarea class="form-control" name="body" rows="4" placeholder="Scrivi qui il tuo commento..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Pubblica commento</button>
            </form>
        </div>
    @else
        <div class="alert alert-info text-center">
            <a href="{{ route('login') }}" class="fw-bold">Accedi</a> o <a href="{{ route('register') }}"
                class="fw-bold">registrati</a> per lasciare un commento.
        </div>
    @endauth

    <div class="existing-comments mt-4">
        @forelse($article->comments->where('is_approved', true)->whereNull('parent_id')->sortByDesc('created_at') as $comment)
            <div class="comment mb-4 p-3 border rounded-3 bg-white shadow-sm">
                {{-- ... struttura del singolo commento e risposte ... --}}
                <div class="d-flex align-items-start mb-2">
                    <div>
                        <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                        <small class="text-muted"
                            title="{{ $comment->created_at->isoFormat('LLLL') }}">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <p class="mb-2">{{ nl2br(e($comment->body)) }}</p>
                @if ($comment->replies->isNotEmpty())
                    @foreach ($comment->replies->where('is_approved', true)->sortBy('created_at') as $reply)
                        <div class="comment reply ps-4 ms-md-3 pt-3 mt-3 border-top">
                            <div class="d-flex align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $reply->user->name }}</h6>
                                    <small class="text-muted"
                                        title="{{ $reply->created_at->isoFormat('LLLL') }}">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="mb-1">{{ nl2br(e($reply->body)) }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        @empty
            <p>Nessun commento per questo articolo. Sii il primo a commentare!</p>
        @endforelse
    </div>
</div>
