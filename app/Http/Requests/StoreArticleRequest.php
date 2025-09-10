<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $articleId = $this->route('article') ? $this->route('article')->id : null;

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('articles', 'title')->ignore($articleId)],
            'body' => 'required|string',
            'rubric_id' => 'required|exists:rubrics,id',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at_date' => 'nullable|date_format:Y-m-d|required_if:status,scheduled',
            'published_at_time' => 'nullable|date_format:H:i|required_if:status,scheduled',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'remove_image' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
            'reading_time' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.unique' => 'Esiste già un articolo con questo titolo.',
            'body.required' => 'Il corpo dell\'articolo è obbligatorio.',
            'rubric_id.required' => 'Devi selezionare una rubrica.',
            'rubric_id.exists' => 'La rubrica selezionata non è valida.',
            'status.required' => 'Lo stato dell\'articolo è obbligatorio.',
            'published_at_date.required_if' => 'La data di pubblicazione è obbligatoria se lo stato è "Pianificato".',
            'published_at_time.required_if' => 'L\'ora di pubblicazione è obbligatoria se lo stato è "Pianificato".',
            'image_path.image' => 'Il file caricato deve essere un\'immagine.',
            'image_path.mimes' => 'L\'immagine deve essere di tipo: jpeg, png, jpg, gif, svg, webp.',
            'image_path.max' => 'L\'immagine non può superare i 4MB.',
        ];
    }
}
