<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        'status',
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
        return $query->where('status', 'Pubblicato')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getDescriptionAttribute($value)
    {
        if (!empty($value)) {
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
            return asset('storage/' . $this->image_path);
        }

        return null;
    }

    public function isPublished(): bool
    {
        return $this->status === 'Pubblicato'
            && $this->published_at !== null
            && $this->published_at <= now();
    }

    public function getLikedByCurrentUserAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}
