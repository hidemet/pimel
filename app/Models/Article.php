<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

// Per lo slug e l'excerpt automatico

class Article extends Model {
    use HasFactory; // Assicura che il trait HasFactory sia presente

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'image_path',
        'published_at',
        'reading_time',
        'status',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',

        // Laravel convertirà automaticamente questo campo in un oggetto Carbon
    ];

    // RELAZIONI ELOQUENT

    /**
     * Un articolo appartiene a un utente (autore).
     */
    public function author(): BelongsTo {
        return $this->belongsTo( User::class, 'user_id' );
        // Specifica 'user_id' come chiave esterna
    }

    /**
     * Un articolo può appartenere a una o più rubriche.
     */
    public function rubrics(): BelongsToMany {
        return $this->belongsToMany( Rubric::class, 'article_rubric' );
        // Nome della tabella pivot

        // Laravel si aspetta 'article_id' e 'rubric_id' nella tabella pivot per convenzione
    }

    /**
     * Un articolo può avere molti commenti.
     */
    public function comments(): HasMany {
        return $this->hasMany( Comment::class )->whereNull( 'parent_id' )
            ->orderBy( 'created_at', 'desc' );
        // Filtra per commenti di primo livello e ordina per i più recenti

        // Le risposte ai commenti verranno gestite tramite il modello Comment stesso
    }

    /**
     * Un articolo può avere molti "likes".
     */
    public function likes(): HasMany {
        // Assumendo un modello ArticleLike per la tabella article_likes
        return $this->hasMany( ArticleLike::class );
    }

    // MUTATORS & ACCESSORS (Opzionali ma utili)

    /**
     * Imposta lo slug automaticamente quando si imposta il titolo.
     */
    public function setTitleAttribute( $value ) {
        $this->attributes['title'] = $value;
        if ( !isset( $this->attributes['slug'] ) || empty( $this
            ->attributes['slug'] ) ) {
            $this->attributes['slug'] = Str::slug( $value );
        }
    }

    /**
     * Genera un excerpt automaticamente dal corpo se non fornito.
     */
    public function getExcerptAttribute( $value ) {
        // Se un excerpt è già stato impostato esplicitamente, usa quello.
        if ( !empty( $value ) ) {
            return $value;
        }
        // Altrimenti, genera un excerpt dal corpo dell'articolo.
        // Str::words() taglia il testo a un certo numero di parole.

        // strip_tags() rimuove eventuali tag HTML dal corpo prima di generare l'excerpt.
        return Str::words( strip_tags( $this->attributes['body'] ), 30, '...'
        );
        // 30 parole, poi '...'
    }

    /**
     * Accessor per ottenere l'URL completo dell'immagine di copertina.
     */
    public function getImageUrlAttribute() {
        if ( $this->image_path ) {
            return asset( 'storage/' . $this->image_path );
        }

        // Puoi restituire un'immagine placeholder di default se nessuna immagine è impostata
        return asset( 'assets/img/placeholder_articolo.png' );
        // Assicurati che questo placeholder esista
    }

    /**
     * Accessor per verificare se l'articolo è pubblicato.
     */
    public function isPublished(): bool {
        return $this->status === 'published' && ( !is_null( $this
                ->published_at )
                && $this->published_at <= now() );
    }

    /**
     * Scope per recuperare solo gli articoli pubblicati.
     * Esempio di utilizzo: Article::published()->get();
     */
    public function scopePublished( $query ) {
        return $query->where( 'status', 'published' )
            ->whereNotNull( 'published_at' )
            ->where( 'published_at', '<=', now() );
    }
}