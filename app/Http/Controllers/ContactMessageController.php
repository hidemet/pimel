<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail; // Se vuoi inviare email
// use App\Mail\ContactMessageReceived;  // Tua classe Mailable
// use Illuminate\Support\Facades\Log;   // Per loggare errori

class ContactMessageController extends Controller {
    public function store(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service_of_interest' => 'nullable|string|max:255', // Potrebbe essere 'altro' o il nome di un servizio
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
            'privacy' => 'required|accepted', // 'accepted' verifica che il campo sia 'yes', 'on', '1', o 'true', o 'accepted'
        ],[
            'name.required' => 'Il campo nome è obbligatorio.',
            'email.required' => 'Il campo email è obbligatorio.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'message.required' => 'Il campo messaggio è obbligatorio.',
            'message.min' => 'Il messaggio deve contenere almeno :min caratteri.',
            'privacy.required' => 'Devi accettare l\'informativa sulla privacy.',
            'privacy.accepted' => 'Devi accettare l\'informativa sulla privacy.',
        ]);

        // Rimuovi 'privacy' prima di creare il messaggio, perché non è nella tabella ContactMessages
        $messageData = collect($validatedData)->except(['privacy'])->toArray();

        ContactMessage::create($messageData);

        // Opzionale: Invia una notifica email all'amministratore
        // try {
        //     $adminEmail = config('mail.admin_address', 'tuo_admin_email@example.com');
        //     Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        // } catch (\Exception $e) {
        //     Log::error("Errore invio email notifica contatto: " . $e->getMessage());
        // }

        return redirect()->route('contatti.form') // Reindirizza alla stessa pagina del form
            ->with('success', 'Messaggio inviato con successo! Ti risponderemo il prima possibile.');
    }
}