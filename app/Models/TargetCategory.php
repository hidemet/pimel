<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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