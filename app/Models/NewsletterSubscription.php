<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rubric> $rubrics
 * @property-read int|null $rubrics_count
 * @method static \Database\Factories\NewsletterSubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
    ];

    public function rubrics(): BelongsToMany
    {
        return $this->belongsToMany(Rubric::class, 'rubric_newsletter_subscription')
            ->withTimestamps();
    }
}
