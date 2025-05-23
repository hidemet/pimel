<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model {
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id', // Importante per le risposte
        'body',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // RELAZIONI ELOQUENT

    /**
     * Un commento appartiene a un articolo.
     */
    public function article(): BelongsTo {
        return $this->belongsTo( Article::class );
    }

    /**
     * Un commento appartiene a un utente (autore del commento).
     */
    public function user(): BelongsTo
    // Rinominato da author() per evitare conflitto se si usa il trait Author
    {
        return $this->belongsTo( User::class );
    }

    /**
     * Un commento può avere un commento genitore (se è una risposta).
     * Relazione ricorsiva.
     */
    public function parent(): BelongsTo {
        return $this->belongsTo( Comment::class, 'parent_id' );
    }

    /**
     * Un commento può avere molte risposte (commenti figli).
     * Relazione ricorsiva.
     */
    public function replies(): HasMany {
        return $this->hasMany( Comment::class, 'parent_id' )
            ->orderBy( 'created_at', 'asc' );
        // Ordina le risposte per data di creazione (le più vecchie prima)
    }

    // SCOPES (Opzionale)

    /**
     * Scope per recuperare solo i commenti approvati.
     * Esempio di utilizzo: Comment::approved()->get();
     */
    public function scopeApproved( $query ) {
        return $query->where( 'is_approved', true );
    }

    /**
     * Scope per recuperare solo i commenti di primo livello (non risposte).
     * Esempio di utilizzo: Comment::topLevel()->get();
     */
    public function scopeTopLevel( $query ) {
        return $query->whereNull( 'parent_id' );
    }
}