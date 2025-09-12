<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentAdminController extends Controller {
    public function index(Request $request): View {
        $currentStatusFilter = $request->input('status', 'pending');

        // Query base per i commenti
        $query = Comment::query()
            ->with(['user', 'article:id,title,slug'])
            ->orderBy('created_at', 'desc');

        // Applica il filtro per stato
        if ('pending' === $currentStatusFilter) {
            $query->where('is_approved', false);
        } else {
            $query->where('is_approved', true);
        }

        $comments = $query->paginate(20)->withQueryString();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Moderazione Commenti'],
        ];

        return view('admin.comments.index', compact(
            'comments',
            'currentStatusFilter',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, Comment $comment): JsonResponse {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $comment->is_approved = $validated['is_approved'];
        $comment->save();

        $newStatusText = $comment->is_approved ? 'approvato' : 'spostato in "in attesa"';
        $message = "Commento #{$comment->id} {$newStatusText} con successo.";

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function destroy(Comment $comment): JsonResponse {
        $commentId = $comment->id;
        $comment->delete();

        $message = "Commento #{$commentId} e le sue eventuali risposte sono stati eliminati con successo.";

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
