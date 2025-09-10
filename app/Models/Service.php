<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'target_audience',
        'objectives',
        'modalities',
        'is_active',
        'target_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function targetCategory(): BelongsTo
    {
        return $this->belongsTo(TargetCategory::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
