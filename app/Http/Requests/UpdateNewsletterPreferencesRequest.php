<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateNewsletterPreferencesRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::check();
    }

    public function rules(): array {
        return [
            'rubriche_selezionate' => 'nullable|array',
            'rubriche_selezionate.*' => 'exists:rubrics,id',
            'select_all_rubriche' => 'nullable|boolean',
        ];
    }

    public function messages(): array {
        return [
            'rubriche_selezionate.*.exists' => 'Una o più rubriche selezionate non sono valide.',
        ];
    }
}
