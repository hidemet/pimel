<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest {
    public function authorize(): bool {
        return \Illuminate\Support\Facades\Auth::check();
    }

    public function rules(): array {
        return [
            'body' => 'required|string|min:3|max:2000',
        ];
    }

    public function messages(): array {
        return [
            'body.required' => 'Il testo del commento è obbligatorio.',
            'body.min' => 'Il commento deve contenere almeno :min caratteri.',
            'body.max' => 'Il commento non può superare i :max caratteri.',
        ];
    }
}
