<?php

namespace App\Models;

use App\Contracts\Transactable;
use App\Models\Traits\HasSignedAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model implements Transactable
{
    use HasFactory, HasSignedAmount;

    protected $fillable = ['user_id', 'card_number', 'requested_amount', 'payout_amount', 'status_id'];

    public function getAmount(): float
    {
        return $this->requested_amount;
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
