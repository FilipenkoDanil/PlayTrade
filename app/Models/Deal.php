<?php

namespace App\Models;

use App\Contracts\Transactable;
use App\Models\Traits\HasSignedAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model implements Transactable
{
    use HasFactory, HasSignedAmount;

    protected $fillable = ['quantity', 'price', 'buyer_id', 'offer_id', 'offer_title', 'offer_description', 'offer_attributes', 'offer_server', 'status_id'];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    public function amount(): float
    {
        return $this->price;
    }

    public function delete(): void
    {
        abort(403, 'Deleting a deal is not allowed.');
    }
}
