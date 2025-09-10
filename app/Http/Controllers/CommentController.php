<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Article; // Importa il modello Article
use Illuminate\Support\Facades\Auth; // Importa Auth

class CommentController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'body' => 'required|string|min:3|max:2000',
        ], [
            'body.required' => 'Il testo del commento è obbligatorio.',
            'body.min' => 'Il commento deve contenere almeno :min caratteri.',
            'body.max' => 'Il commento non può superare i :max caratteri.',
        ]);

        $comment = new Comment();
        $comment->body = $validatedData['body'];
        $comment->user_id = Auth::id();
        $comment->article_id = $article->id;
        $comment->is_approved = Auth::user()->isAdmin();

        $comment->save();

        if ($comment->is_approved) {
            $toastData = [
                'type'    => 'success',
                'title'   => 'Commento Pubblicato!',
                'message' => 'Il tuo commento è stato pubblicato.',
            ];
        } else {
            $toastData = [
                'type'    => 'success',
                'title'   => 'Commento Inviato!',
                'message' => 'Il tuo commento è stato ricevuto e sarà visibile dopo l\'approvazione.',
            ];
        }

        return redirect()->route('blog.show', ['article' => $article->slug, '#comments'])
            ->with('toast', $toastData);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
