<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'type', 'description', 'game_id', 'unit_id'];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->allOffers()->delete();
        });
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class)->where('is_active', '=', 1);
    }

    public function allOffers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
