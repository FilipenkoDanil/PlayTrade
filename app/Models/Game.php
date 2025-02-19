<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title'];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
