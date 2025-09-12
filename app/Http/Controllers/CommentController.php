<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    public function store(StoreCommentRequest $request, Article $article): RedirectResponse {
        $comment = Comment::create([
            'body' => $request->validated()['body'],
            'user_id' => Auth::id(),
            'article_id' => $article->id,
            'is_approved' => Auth::user()->isAdmin(),
        ]);

        $toastData = $comment->is_approved
            ? [
                'type' => 'success',
                'title' => 'Commento Pubblicato!',
                'message' => 'Il tuo commento è stato pubblicato.',
            ]
            : [
                'type' => 'success',
                'title' => 'Commento Inviato!',
                'message' => 'Il tuo commento è stato ricevuto e sarà visibile dopo l\'approvazione.',
            ];

        return redirect()->route('blog.show', ['article' => $article->slug, '#comments'])
            ->with('toast', $toastData);
    }
}
