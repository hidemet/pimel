{{-- resources/views/blog/partials/_comment.blade.php --}}

@props([
  'comment',
  'article',
  'isReply' => false,
])

<div
  class="comment-item @if ($isReply) comment-reply mb-3 pt-2 @if (!$loop->last) border-bottom border-opacity-25 @endif @else px-0 py-3 @if (!$loop->last) border-bottom @endif @endif"
  id="comment-{{ $comment->id }}"
>
  <div class="d-flex align-items-start @if ($isReply) mb-1 @else mb-2 @endif">
    <div class="flex-shrink-0 @if ($isReply) me-2 @else me-3 @endif">
      <span
        class="material-symbols-outlined text-secondary @if ($isReply) fs-2 @else fs-1 @endif"
      >
        account_circle
      </span>
    </div>
    <div class="flex-grow-1">
      <h6 class="mb-0 fw-semibold @if ($isReply) small @endif">
        {{ $comment->user->name }}
        @if (! $comment->is_approved && Auth::check() && $comment->user_id == Auth::id())
          <span class="badge bg-info text-dark ms-1 small">
            @if ($isReply)
              In attesa
            @else
                In attesa di approvazione
            @endif
          </span>
        @endif
      </h6>
      <small
        class="text-muted"
        title="{{ $comment->created_at->isoFormat('LLLL') }}"
      >
        {{ $comment->created_at->diffForHumans() }}
      </small>
    </div>
  </div>
  <div class="@if ($isReply) ps-reply-body @else ps-comment-body @endif">
    <p
      class="mb-2 @if ($isReply) small @endif @if (!$comment->is_approved) text-muted fst-italic @endif"
    >
      {{ nl2br(e($comment->body)) }}
    </p>
  </div>
</div>
