<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
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
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Un commento appartiene a un utente (autore del commento).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES

    /**
     * Scope per recuperare solo i commenti approvati.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
