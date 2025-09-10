<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
