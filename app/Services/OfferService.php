<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update(Offer $offer, array $data): Offer
    {
        $offer->update($data);

        return $offer;
    }
}
