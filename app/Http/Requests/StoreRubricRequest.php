<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRubricRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->name && ! $this->slug) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }


    public function rules(): array
    {
        // Usiamo l'operatore null-safe (?->) per evitare errori se la rotta non ha il parametro.
        $rubricId = $this->route('rubric')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rubrics', 'name')->ignore($rubricId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rubrics', 'slug')->ignore($rubricId),
            ],
        ];
    }

    // messaggi d'errore personalizzati per le regole di validazione
    public function messages(): array
    {
        return [
            'name.required' => 'Il nome della rubrica è obbligatorio.',
            'name.unique' => 'Esiste già una rubrica con questo nome.',
            'slug.required' => 'Lo slug è obbligatorio.',
            'slug.unique' => 'Esiste già una rubrica con questo slug (indirizzo web).',
            'slug.alpha_dash' => 'Lo slug può contenere solo lettere, numeri, trattini e underscore.',
        ];
    }
}
