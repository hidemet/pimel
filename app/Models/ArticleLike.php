<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $article_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article $article
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ArticleLikeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArticleLike whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
