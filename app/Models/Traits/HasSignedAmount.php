<?php

namespace App\Models\Traits;

trait HasSignedAmount
{
    public function getSignedAmount(string $type): float
    {
        return match ($type) {
            'deal_purchase', 'withdrawal' => -abs($this->amount()),
            'deal_sale', 'deal_canceled', 'deposit' => abs($this->amount()),
            default => throw new \InvalidArgumentException("Unknown transaction type: $type"),
        };
    }
}
