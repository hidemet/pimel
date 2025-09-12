<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

class ContactMessageController extends Controller {
    public function store(StoreContactMessageRequest $request): RedirectResponse {
        ContactMessage::create($request->validated());

        return redirect()->route('contatti.form')
            ->with('success', 'Messaggio inviato con successo! Ti risponderemo il prima possibile.');
    }
}
