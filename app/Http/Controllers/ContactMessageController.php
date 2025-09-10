<?php

namespace App\Http\Controllers;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller {
    public function store(Request $request): RedirectResponse {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service_of_interest' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ],[
            'name.required' => 'Il campo nome è obbligatorio.',
            'email.required' => 'Il campo email è obbligatorio.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'message.required' => 'Il campo messaggio è obbligatorio.',
            'message.min' => 'Il messaggio deve contenere almeno :min caratteri.',
        ]);

        ContactMessage::create($validatedData);



        return redirect()->route('contatti.form')
            ->with('success', 'Messaggio inviato con successo! Ti risponderemo il prima possibile.');
    }
}
