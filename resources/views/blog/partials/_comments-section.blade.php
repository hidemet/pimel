{{-- resources/views/blog/partials/_comments-section.blade.php --}}

@props([
  'article',
  'comments',
  'pendingComments',
  'totalVisibleComments',
])

<div
  class="comments-section my-5"
  id="comments"
>
  <h3 class="mb-4 h4">Commenti ({{ $totalVisibleComments }})</h3>

  @auth
    {{-- Form per lasciare un commento principale --}}
    <div class="card mb-4 p-3 p-md-4 rounded-4">
      <h5 class="mb-3">Lascia un commento</h5>
      <form
        action="{{ route('comments.store', ['article' => $article->id]) }}"
        method="POST"
        id="mainCommentForm"
      >
        @csrf
        <div class="mb-3">
          <textarea
            class="form-control @error('body', 'commentStore') is-invalid @enderror"
            name="body"
            rows="3"
            placeholder="Scrivi qui il tuo commento..."
            required
          >
{{ old('body') }}</textarea
          >
          @error('body', 'commentStore')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <button
          type="submit"
          class="btn btn-primary rounded-pill px-4"
        >
          Pubblica commento
        </button>
      </form>
    </div>

    {{-- Mostra i commenti dell'utente in attesa di approvazione --}}
    @if ($pendingComments->isNotEmpty())
      <div class="pending-comments-user mb-4">
        <h5 class="h6 text-muted mb-2">
          I tuoi commenti in attesa di approvazione:
        </h5>
        @foreach ($pendingComments as $pendingComment)
          @include(
            'blog.partials._comment',
            [
              'comment' => $pendingComment,
              'article' => $article,
            ]
          )
        @endforeach
      </div>
    @endif
  @else
    <div class="alert alert-info text-center small py-2">
      <a
        href="{{ route('login') }}"
        class="fw-bold"
      >
        Accedi
      </a>
      o
      <a
        href="{{ route('register') }}"
        class="fw-bold"
      >
        registrati
      </a>
      per lasciare un commento.
    </div>
  @endauth

  <div class="existing-comments mt-4">
    @if ($comments->isNotEmpty())
      <div>
        @foreach ($comments as $comment)
          @include(
            'blog.partials._comment',
            [
              'comment' => $comment,
              'article' => $article,
            ]
          )
        @endforeach
      </div>
    @else
      <div class="text-center py-4 text-muted border rounded-3 bg-light">
        <span class="material-symbols-outlined fs-2 mb-2 d-block">
          chat_bubble_outline
        </span>
        Nessun commento approvato per questo articolo.
        @guest
          <br />
          Sii il primo a lasciare il tuo pensiero (dopo aver effettuato
          l'accesso)!
        @endguest
      </div>
    @endif
  </div>
</div>

@push('scripts')
  {{-- JavaScript per commenti rimosso - nessuna interazione necessaria --}}
@endpush
