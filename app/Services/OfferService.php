<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public function create(array $data): Offer
    {
        $offer = Offer::create($data);

        $this->syncAttributes($offer, $data['attributes']);

        return $offer;
    }

    public function update(Offer $offer, array $data): Offer
    {
        $offer->update($data);

        $this->syncAttributes($offer, $data['attributes']);

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
