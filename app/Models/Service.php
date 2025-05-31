<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Support\Str;
// Per lo slug

class Service extends Model {
    use HasFactory; // Assicura che il trait HasFactory sia presente

    protected $fillable = [
        'name',
        'slug',
        'description',
        'target_audience',
        'objectives',
        'modalities',
        'is_active',
        'target_category_id', // Aggiunto

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        // Laravel convertirà automaticamente questo in true/false
    ];

    // RELAZIONI ELOQUENT
    // Per ora, la tabella 'services' non ha relazioni dirette definite
    // con altre tabelle tramite chiavi esterne in questa tabella.

    // Se in futuro un servizio fosse collegato, ad esempio, a ordini o prenotazioni,
    // aggiungeremmo qui le relazioni appropriate (es. hasMany(Order::class)).

    // Potrebbe esserci una relazione con contact_messages se 'service_of_interest'
    // fosse una FK, ma l'abbiamo definita come stringa per flessibilità.

    // MUTATORS & ACCESSORS (Opzionale, per lo slug)

     public function targetCategory(): BelongsTo
    {
        return $this->belongsTo(TargetCategory::class);
    }

    /**
     * Imposta lo slug automaticamente quando si imposta il nome.
     */
    public function setNameAttribute( $value ) {
        $this->attributes['name'] = $value;
        if ( empty( $this->attributes['slug'] ) ) { // Genera slug solo se è vuoto
            $this->attributes['slug'] = Str::slug( $value );
        }
    }

    /**
     * Accessor per ottenere un URL per il servizio (se avessi una pagina dedicata per ogni servizio).
     * Esempio: $service->link
     * Richiede una rotta nominata, ad esempio 'services.show'.
     */
    // public function getLinkAttribute()
    // {
    //     return route('services.show', $this->slug);
    // }

    // SCOPES (Opzionale)

    /**
     * Scope per recuperare solo i servizi attivi.
     * Esempio di utilizzo: Service::active()->get();
     */
    public function scopeActive( $query ) {
        return $query->where( 'is_active', true );
    }
}