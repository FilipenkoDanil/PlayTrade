<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class DealService
{
    public function create(array $data): ?Deal
    {
        $offer = Offer::with('attributes')->findOrFail($data['offer_id']);

        if ($this->isOfferValid($offer, $data['quantity'])) {
            $deal = Deal::create([
                'quantity' => $data['quantity'],
                'price' => $offer->price * $data['quantity'],
                'buyer_id' => 1, //TODO: Auth::id()
                'offer_id' => $offer->id,
                'offer_title' => $offer->title,
                'offer_description' => $offer->description,
                'offer_attributes' => $offer->attributes,
                'offer_server' => $offer->server,
                'status_id' => 1,
            ]);

            $offer->amount -= $deal->quantity;
            $offer->save();

            return $deal;
        }

        return null;
    }


    private function isOfferValid(Offer $offer, int $quantity): bool
    {
        return $this->checkAmount($offer, $quantity) && $this->checkBuyerNotSeller($offer) && $this->isActive($offer);
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
}
