<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriptionRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'email' => 'required|email|max:255|unique:newsletter_subscriptions,email',
            'rubriche_selezionate' => 'nullable|array',
            'rubriche_selezionate.*' => 'exists:rubrics,id',
            'select_all_rubriche' => 'nullable|boolean',
        ];
    }

    public function messages(): array {
        return [
            'email.unique' => 'Questo indirizzo email è già iscritto alla newsletter.',
            'rubriche_selezionate.*.exists' => 'Una delle rubriche selezionate non è valida.',
        ];
    }
}
