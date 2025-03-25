<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'amount', 'price', 'description', 'auto_message', 'is_active', 'category_id', 'seller_id', 'server_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class)->withPivot('value');
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
