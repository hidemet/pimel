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
        $comment->is_approved = false; // <-- MODIFICA: In attesa di approvazione
        // parent_id sarà null per i commenti di primo livello.

        $comment->save();

        // Prepara i dati per il toast
        $toastData = [
            'type'    => 'success', // 'success', 'info', 'warning', 'error'
            'title'   => 'Commento Inviato!',
            'message' => 'Il tuo commento è stato ricevuto e sarà visibile dopo l\'approvazione.',
        ];

        // Invece di ->with('success', ...), passiamo i dati del toast alla sessione.
        // Il redirect include ancora l'ancora #comments.
        return redirect()->route('blog.show', ['article' => $article->slug, '#comments'])
                         ->with('toast', $toastData);
    }

    public function storeReply(Request $request, Article $article, Comment $parentComment)
    {
        // Validazione per il corpo della risposta
        // Usiamo un error bag diverso per non confonderci con il form principale dei commenti
        // se entrambi fossero aperti e avessero errori.
        // Per fare ciò, dovremmo modificare il nome del campo nel form di risposta, es. "reply_body"
        // e validare "reply_body". Per ora, manteniamo "body" e Laravel userà il default error bag.
        $validatedData = $request->validate([
            'body' => 'required|string|min:1|max:1000', // Regole leggermente diverse per le risposte?
        ], [
            'body.required' => 'Il testo della risposta è obbligatorio.',
            'body.min'      => 'La risposta deve contenere almeno :min carattere.',
            'body.max'      => 'La risposta non può superare i :max caratteri.',
        ]);

        // Crea la risposta
        $reply = new Comment();
        $reply->body = $validatedData['body'];
        $reply->user_id = Auth::id();
        $reply->article_id = $article->id;       // L'articolo è lo stesso del commento genitore
        $reply->parent_id = $parentComment->id;  // Imposta il parent_id
        $reply->is_approved = false;             // Anche le risposte attendono approvazione

        $reply->save();

        $toastData = [
            'type'    => 'success',
            'title'   => 'Risposta Inviata!',
            'message' => 'La tua risposta è stata ricevuta e sarà visibile dopo l\'approvazione.',
        ];

        // Reindirizza alla pagina dell'articolo, idealmente scrollando al commento genitore o alla nuova risposta.
        // Scrollare direttamente alla risposta appena creata è più complesso senza AJAX.
        // Scrollare al commento genitore è un buon compromesso.
        return redirect()->route('blog.show', ['article' => $article->slug, '#comment-' . $parentComment->id]) //  Puoi creare un id="comment-{{$comment->id}}" sul div del commento
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
