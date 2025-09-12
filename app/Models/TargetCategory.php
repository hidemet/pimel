<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon_class
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @method static \Database\Factories\TargetCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TargetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon_class',
        'description',
    ];

    /**
     * Una categoria di target può avere molti servizi.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Imposta lo slug automaticamente quando si imposta il nome.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}
