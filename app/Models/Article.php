<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $rubric_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string $body
 * @property string|null $image_path
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $reading_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $image_url
 * @property-read bool $liked_by_current_user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArticleLike> $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\Rubric|null $rubric
 * @method static \Database\Factories\ArticleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article orderByDate($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article search($searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereReadingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereRubricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rubric_id',
        'title',
        'slug',
        'description',
        'body',
        'image_path',
        'published_at',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ArticleLike::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getDescriptionAttribute($value)
    {
        if (! empty($value)) {
            return $value;
        }

        if (isset($this->attributes['body'])) {
            return Str::words(strip_tags($this->attributes['body']), 30, '...');
        }

        return '';
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/'.$this->image_path);
        }

        return null;
    }

    public function isPublished(): bool
    {
        return null !== $this->published_at
            && $this->published_at <= now();
    }

    public function getLikedByCurrentUserAttribute(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    /**
     * Scope per la ricerca testuale su titolo e autore.
     */
    public function scopeSearch($query, $searchTerm)
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm) {
            $q->where('articles.title', 'like', '%'.$searchTerm.'%')
                ->orWhereHas('author', function ($subQ) use ($searchTerm) {
                    $subQ->where('users.name', 'like', '%'.$searchTerm.'%');
                });
        });
    }

    /**
     * Scope per ordinamento per data di pubblicazione.
     */
    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('published_at', 'asc' === $direction ? 'asc' : 'desc');
    }
}
