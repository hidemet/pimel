<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
// Per generare il token

class NewsletterSubscription extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'newsletter_subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token',
        'confirmed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'confirmed_at' => 'datetime', // Tratta come oggetto Carbon
    ];

    // RELAZIONI ELOQUENT

    /**
     * Le rubriche a cui un iscritto è interessato.
     * Relazione Molti-a-Molti.
     */
    public function rubrics(): BelongsToMany {
        return $this->belongsToMany( Rubric::class,
            'rubric_newsletter_subscription' )

        // Laravel deduce le chiavi esterne 'newsletter_subscription_id' e 'rubric_id'

        // nella tabella pivot 'rubric_newsletter_subscription' per convenzione.
            ->withTimestamps();
        // Se la tabella pivot ha timestamps e vuoi gestirli

        // tramite la relazione (es. quando fai attach/sync).

        // La nostra migrazione per la pivot include timestamps().
    }

    // METODI HELPER / LOGICA DI BUSINESS

    /**
     * Genera un token univoco per la sottoscrizione.
     * Può essere chiamato prima di salvare un nuovo record.
     * Esempio: $subscription->generateToken(); $subscription->save();
     */
    public function generateToken(): void {
        $this->token = Str::random( 60 ); // Genera una stringa casuale sicura
    }

    /**
     * Conferma l'iscrizione.
     */
    public function confirm(): bool {
        if ( !$this->confirmed_at ) {
            $this->confirmed_at = now();
            $this->token        = null;
            // Opzionale: annulla il token dopo la conferma
            return $this->save();
        }
        return false; // Già confermato o nessun cambiamento
    }

    /**
     * Verifica se l'iscrizione è confermata.
     */
    public function isConfirmed(): bool {
        return !is_null( $this->confirmed_at );
    }

    // SCOPES (Opzionale)

    /**
     * Scope per recuperare solo le iscrizioni confermate.
     * Esempio di utilizzo: NewsletterSubscription::confirmed()->get();
     */
    public function scopeConfirmed( $query ) {
        return $query->whereNotNull( 'confirmed_at' );
    }

    /**
     * Scope per recuperare solo le iscrizioni non confermate.
     * Esempio di utilizzo: NewsletterSubscription::unconfirmed()->get();
     */
    public function scopeUnconfirmed( $query ) {
        return $query->whereNull( 'confirmed_at' );
    }
}