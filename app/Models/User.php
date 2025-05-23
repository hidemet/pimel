<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // RELAZIONI ELOQUENT

    /**
     * Un utente può scrivere molti articoli.
     */
    public function articles() {
        return $this->hasMany( Article::class );
    }

    /**
     * Un utente può scrivere molti commenti.
     */
    public function comments() {
        return $this->hasMany( Comment::class );
    }

    /**
     * Un utente può mettere "like" a molti articoli.
     * Questa relazione usa la tabella pivot 'article_likes'.
     * Possiamo definirla come una belongsToMany se vogliamo accedere ai "like"
     * come una collezione di articoli a cui l'utente ha messo like.
     * Oppure, una hasMany diretta alla tabella 'article_likes' se vogliamo i record dei like.
     * Scegliamo la hasMany diretta al modello ArticleLike (che creeremo).
     */
    public function likes() {

        // Assumendo che creeremo un modello ArticleLike per la tabella article_likes
        return $this->hasMany( ArticleLike::class );
    }

    // METODI HELPER PER IL RUOLO (OPZIONALE MA UTILE)

    /**
     * Verifica se l'utente è un amministratore.
     */
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
}