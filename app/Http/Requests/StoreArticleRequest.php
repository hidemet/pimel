<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreArticleRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    protected function prepareForValidation(): void {
        // se l'utente ha scritto uno slug viene normalizzato
        // es. "articolo di prova" diventa "articolo-di-prova"
        if ($this->slug) {
            $this->merge([
                'slug' => Str::slug($this->slug),
            ]);
        }
    }

    public function rules(): array {
        $articleId = $this->route('article') ? $this->route('article')->id : null;

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('articles', 'title')->ignore($articleId)],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($articleId)
            ],
            'body' => 'required|string',
            'rubric_id' => 'required|exists:rubrics,id',
            'published_at' => 'required|date|before_or_equal:now',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'remove_image' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
            'reading_time' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.unique' => 'Esiste già un articolo con questo titolo.',
            'slug.required' => 'Lo slug è obbligatorio. Puoi scriverlo manualmente o usare il pulsante per sbloccarlo e generarlo dal titolo.',
            'slug.unique' => 'Questo slug è già utilizzato da un altro articolo. Scegline uno diverso.',
            'body.required' => 'Il corpo dell\'articolo è obbligatorio.',
            'rubric_id.required' => 'Devi selezionare una rubrica.',
            'rubric_id.exists' => 'La rubrica selezionata non è valida.',
            'published_at.required' => 'La data di pubblicazione è obbligatoria.',
            'published_at.date' => 'La data di pubblicazione deve essere una data valida.',
            'published_at.before_or_equal' => 'La data di pubblicazione non può essere nel futuro.',
            'image_path.image' => 'Il file caricato deve essere un\'immagine.',
            'image_path.mimes' => 'L\'immagine deve essere di tipo: jpeg, png, jpg, gif, svg, webp.',
            'image_path.max' => 'L\'immagine non può superare i 4MB.',
        ];
    }
}
