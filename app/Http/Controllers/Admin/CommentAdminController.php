<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CommentAdminController extends Controller
{
    /**
     * Mostra la lista dei commenti con filtri e paginazione.
     */
    public function index(Request $request): View
    {
        // Valori di default per i filtri
        $currentStatusFilter = $request->input('status', 'pending'); // Default: 'pending'

        // Conteggi per i pulsanti di filtro
        $statusCounts = Comment::query()
            ->selectRaw('is_approved, count(*) as total')
            ->groupBy('is_approved')
            ->pluck('total', 'is_approved');

        $commentStatuses = [
            'pending'   => ['text' => 'In Attesa', 'count' => $statusCounts->get(0, 0)],
            'approved'  => ['text' => 'Approvati', 'count' => $statusCounts->get(1, 0)],
        ];

        // Query base per i commenti
        $query = Comment::query()
            ->with(['user', 'article:id,title,slug']) // Eager loading per evitare N+1
            ->orderBy('created_at', 'desc');

        // Applica il filtro per stato
        if ($currentStatusFilter === 'pending') {
            $query->where('is_approved', false);
        } else {
            $query->where('is_approved', true);
        }
        
        $comments = $query->paginate(20)->withQueryString();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Moderazione Commenti']
        ];

        return view('admin.comments.index', compact(
            'comments',
            'commentStatuses',
            'currentStatusFilter',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
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

    /**
     * Rimuove un commento dal database.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $commentId = $comment->id;
        $comment->delete();

        $message = "Commento #{$commentId} e le sue eventuali risposte sono stati eliminati con successo.";

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
