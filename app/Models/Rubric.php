<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NewsletterSubscription> $newsletterSubscriptions
 * @property-read int|null $newsletter_subscriptions_count
 * @method static \Database\Factories\RubricFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric search($searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rubric whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function newsletterSubscriptions(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscription::class, 'rubric_newsletter_subscription');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (! isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /**
     * Scope per la ricerca testuale su nome e descrizione.
     */
    public function scopeSearch($query, $searchTerm)
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%'.$searchTerm.'%')
                ->orWhere('description', 'like', '%'.$searchTerm.'%');
        });
    }
}
