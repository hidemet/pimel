<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel dedurrebbe 'contact_messages', ma esplicitarlo è una buona pratica.
     *
     * @var string
     */
    protected $table = 'contact_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'service_of_interest',
        'is_read',
        'archived_at',
        // Anche se questo viene gestito tramite metodi, è bene averlo fillable se lo imposti manualmente
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_read'     => 'boolean',
        'archived_at' => 'datetime', // Per trattarlo come oggetto Carbon
    ];

    // RELAZIONI ELOQUENT

    // In questo momento, ContactMessage non ha relazioni dirette definite CHE PARTONO DA ESSO.
    // Se 'service_of_interest' fosse una FK a 'services.id', qui avremmo:
    // public function service()
    // {

    //     return $this->belongsTo(Service::class, 'service_of_interest_id'); // Se fosse un ID
    // }
    // Ma dato che è una stringa, non c'è una relazione Eloquent diretta.

    // SCOPES (Opzionale ma utile per l'admin)

    /**
     * Scope per recuperare solo i messaggi non letti.
     * Esempio di utilizzo: ContactMessage::unread()->get();
     */
    public function scopeUnread( $query ) {
        return $query->where( 'is_read', false );
    }

    /**
     * Scope per recuperare solo i messaggi non archiviati.
     * Esempio di utilizzo: ContactMessage::unarchived()->get();
     */
    public function scopeUnarchived( $query ) {
        return $query->whereNull( 'archived_at' );
    }

    /**
     * Scope per recuperare solo i messaggi archiviati.
     * Esempio di utilizzo: ContactMessage::archived()->get();
     */
    public function scopeArchived( $query ) {
        return $query->whereNotNull( 'archived_at' );
    }

    // METODI HELPER (Opzionale)

    /**
     * Segna il messaggio come letto.
     */
    public function markAsRead(): bool {
        return $this->update( ['is_read' => true] );
    }

    /**
     * Segna il messaggio come non letto.
     */
    public function markAsUnread(): bool {
        return $this->update( ['is_read' => false] );
    }

    /**
     * Archivia il messaggio.
     */
    public function archive(): bool {
        return $this->update( ['archived_at' => now()] );
    }

    /**
     * Rimuove il messaggio dall'archivio.
     */
    public function unarchive(): bool {
        return $this->update( ['archived_at' => null] );
    }
}