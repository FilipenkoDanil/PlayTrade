<?php

namespace App\Http\Resources\Deal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'price' => $this->price,
            'offer_game' => $this->offer_game,
        ];
    }
}
