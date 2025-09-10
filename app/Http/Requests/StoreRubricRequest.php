<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreRubricRequest extends FormRequest
{
    /**
     * Determina se l'utente è autorizzato a effettuare questa richiesta.
     * Solo gli admin possono creare/modificare rubriche.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Prepara i dati per la validazione.
     * In questo caso, generiamo lo slug se non è stato fornito manualmente.
     */
    protected function prepareForValidation(): void
    {
        if ($this->name && !$this->slug) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }

    /**
     * Ottiene le regole di validazione che si applicano alla richiesta.
     */
    public function rules(): array
    {
        // Se stiamo aggiornando, this->route('rubric') conterrà l'istanza della rubrica.
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
                'alpha_dash', // permette lettere, numeri, trattini e underscore
                Rule::unique('rubrics', 'slug')->ignore($rubricId),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Ottiene i messaggi di errore personalizzati per le regole di validazione.
     */
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
