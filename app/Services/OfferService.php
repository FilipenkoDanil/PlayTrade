<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class OfferService
{
    public function create(array $data): Offer
    {
        $data['seller_id'] = Auth::id();

        $offer = Offer::create($data);

        if (isset($data['attributes']))
        {
            $this->syncAttributes($offer, $data['attributes']);
        }

        return $offer;
    }

    public function update(Offer $offer, array $data): Offer
    {
        $offer->update($data);

        if (isset($data['attributes']))
        {
            $this->syncAttributes($offer, $data['attributes']);
        }

        return $offer;
    }

    private function syncAttributes(Offer $offer, array $attributes): void
    {
        $data = [];
        foreach ($attributes as $attribute) {
            $data[$attribute['id']] = ['value' => $attribute['value']];
        }

        $offer->attributes()->sync($data);
    }
}
