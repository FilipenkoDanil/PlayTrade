<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class DealValidator
{
    public function validateCreation(Offer $offer, int $quantity): ?string
    {
        if (!$this->checkAmount($offer, $quantity)) {
            return 'Not enough items in stock.';
        }

        if (!$this->checkBuyerNotSeller($offer)) {
            return 'You cannot buy your own offer.';
        }

        if (!$this->isActive($offer)) {
            return 'The offer is no longer active.';
        }

        if (!$this->checkBalance(Auth::user(), $offer->price * $quantity)) {
            return 'Insufficient balance.';
        }

        return null;
    }

    private function checkAmount(Offer $offer, int $quantity): bool
    {
        return $offer->amount >= $quantity;
    }

    private function checkBuyerNotSeller(Offer $offer): bool
    {
        return $offer->seller_id != Auth::id();
    }

    private function isActive(Offer $offer): bool
    {
        return $offer->is_active;
    }

    private function checkBalance($user, float $price): bool
    {
        return $user->balance >= $price;
    }
}
