<?php

namespace App\Actions\Articles;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaveArticleAction
{
    /**
     * Gestisce la creazione o l'aggiornamento di un articolo, inclusi i suoi dati e le relazioni.
     *
     * @param Article $article L'istanza dell'articolo (nuova o da aggiornare).
     * @param array $validatedData I dati validati provenienti dalla Form Request.
     * @param Request $request L'istanza della richiesta HTTP completa per gestire l'upload dei file.
     * @return Article L'istanza dell'articolo salvato.
     */
    public function execute(Article $article, array $validatedData, Request $request): Article
    {
        // Se l'articolo è nuovo, assegna l'ID dell'utente admin attualmente loggato.
        if (!$article->exists) {
            $article->user_id = Auth::id();
        }
        $article->fill($validatedData);

        // 2. Gestione specifica dello stato e della data di pubblicazione.
        $newStatus = $validatedData['status'];

        if ($newStatus === 'Pubblicato' && !$article->published_at) {
            $article->published_at = now();
        } elseif ($newStatus === 'Programmata' && isset($validatedData['published_at_date'], $validatedData['published_at_time'])) {
            $dateTimeString = $validatedData['published_at_date'] . ' ' . $validatedData['published_at_time'];
            $article->published_at = Carbon::createFromFormat('Y-m-d H:i', $dateTimeString, config('app.display_timezone'))->setTimezone('UTC');
        } elseif (in_array($newStatus, ['Bozza', 'Archiviato'])) {
            $article->published_at = null;
        }

        // 3. Gestione dell'immagine di copertina.
        if ($request->boolean('remove_image')) {
            // Se l'utente ha spuntato "Rimuovi immagine"
            if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
                Storage::disk('public')->delete($article->image_path);
            }
            $article->image_path = null;
        } elseif ($request->hasFile('image_path')) {
            // Se è stata caricata una nuova immagine
            // Rimuovi la vecchia immagine, se presente.
            if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
                Storage::disk('public')->delete($article->image_path);
            }
            // Salva la nuova immagine e aggiorna il percorso.
            $path = $request->file('image_path')->store('article_images', 'public');
            $article->image_path = $path;
        }

        // 4. Salva l'articolo nel database.
        $article->save();

        // 5. Gestione delle relazioni.
        // La vecchia logica di sync() non è più necessaria.
        // L'assegnazione di 'rubric_id' è già stata gestita da $article->fill().
        // Il codice è ora più semplice e pulito.

        // 6. Ritorna l'articolo salvato.
        return $article;
    }
}
