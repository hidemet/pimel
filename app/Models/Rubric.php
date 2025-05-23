<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// Per lo slug

class Rubric extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // RELAZIONI ELOQUENT

    /**
     * Una rubrica può avere molti articoli.
     * Ora che un articolo può avere molte rubriche (e viceversa),
     * questa diventa una relazione belongsToMany.
     */
    public function articles() {
        return $this->belongsToMany( Article::class, 'article_rubric' );
        // Specificare il nome della tabella pivot

        // e opzionalmente le chiavi esterne se non seguono la convenzione

        // (ma in questo caso 'article_id' e 'rubric_id' la seguono)
    }

    /**
     * Una rubrica può essere preferita da molti iscritti alla newsletter.
     */
    public function newsletterSubscriptions() {
        return $this->belongsToMany( NewsletterSubscription::class,
            'rubric_newsletter_subscription' );

        // Nome tabella pivot aggiornato alla convenzione
    }

    // MUTATORS & ACCESSORS (Opzionale, per lo slug)

    /**
     * Imposta lo slug automaticamente quando si imposta il nome.
     * Questo è un modo, un altro è usare un Observer o un Trait.
     */
    public function setNameAttribute( $value ) {
        $this->attributes['name'] = $value;
        if ( !isset( $this->attributes['slug'] ) || empty( $this
            ->attributes['slug'] ) ) {
            $this->attributes['slug'] = Str::slug( $value );
        }
    }

    /**
     * Permette di usare $rubric->link invece di route('rubrics.show', $rubric->slug)
     * (richiede una rotta nominata 'rubrics.show' o simile)
     */
    // public function getLinkAttribute()
    // {

    //     return route('articles.index', ['rubric' => $this->slug]); // Esempio per filtrare articoli per rubrica
    // }
}