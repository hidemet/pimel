<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleLike extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel dedurrebbe 'article_likes' (plurale snake_case),
     * ma specificarlo è una buona pratica per chiarezza.
     *
     * @var string
     */
    protected $table = 'article_likes';

    /**
     * The attributes that are mass assignable.
     * Un "like" è definito dalla sua associazione utente-articolo.
     * Questi verranno solitamente impostati quando si crea il record.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'article_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     * La nostra migrazione include timestamps(), quindi questo è true di default.
     * Se avessimo omesso timestamps() nella migrazione e non li volessimo,
     * imposteremmo public $timestamps = false;
     *
     * @var bool
     */
    // public $timestamps = true; // Default, non serve specificarlo se è true

    // RELAZIONI ELOQUENT

    /**
     * Un "like" appartiene a un utente.
     */
    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }

    /**
     * Un "like" appartiene a un articolo.
     */
    public function article(): BelongsTo {
        return $this->belongsTo( Article::class );
    }
}