<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo gli utenti amministratori possono creare o modificare i servizi.
        return $this->user()->isAdmin();
    }


    public function rules(): array
    {
        // L'ID del servizio è necessario per ignorare il servizio stesso durante la validazione 'unique' in fase di update.
        $serviceId = $this->route('service') ? $this->route('service')->id : null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('services')->ignore($serviceId)],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services')->ignore($serviceId)],
            'description' => 'required|string',
            'target_audience' => 'nullable|string|max:5000', // Aumentato limite
            'objectives' => 'nullable|string|max:5000',      // Aumentato limite
            'modalities' => 'nullable|string|max:5000',      // Aumentato limite
            'target_category_id' => 'required|exists:target_categories,id',
        ];
    }

    public function attributes(): array
    {
        // metodo per rendere i messaggi di errore più leggibili
        // senza dover scrivere ogni singolo messaggio in un metodo messages().
        return [
            'name' => 'nome servizio',
            'slug' => 'slug',
            'description' => 'descrizione',
            'target_audience' => 'pubblico di riferimento',
            'objectives' => 'obiettivi',
            'modalities' => 'modalità di erogazione',
            'target_category_id' => 'categoria target',
        ];
    }
}
