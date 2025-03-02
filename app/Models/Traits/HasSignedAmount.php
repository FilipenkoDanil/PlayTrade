<?php

namespace App\Models\Traits;

trait HasSignedAmount
{
    public function getSignedAmount(string $type): float
    {
        return match ($type) {
            'deal_purchase', 'withdrawal' => -abs($this->getAmount()),
            'deal_sale', 'deal_canceled', 'deposit' => abs($this->getAmount()),
            default => throw new \InvalidArgumentException("Unknown transaction type: $type"),
        };
    }
}
