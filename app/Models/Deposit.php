<?php

namespace App\Models;

use App\Contracts\Transactable;
use App\Models\Traits\HasSignedAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model implements Transactable
{
    use HasFactory, HasSignedAmount;

    protected $fillable = ['user_id', 'deposit_id', 'status_id', 'amount'];

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
