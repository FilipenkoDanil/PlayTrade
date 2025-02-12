<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'buyer_id' => $this->buyer_id,
            'offer_id' => $this->offer_id,
            'status_id' => $this->status_id,
            'offer_title' => $this->offer_title,
            'offer_description' => $this->offer_description,
            'offer_attributes' => $this->offer_attributes,
            'offer_server' => $this->offer_server,
            'created_at' => $this->created_at->diffForHumans(),
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'offer' => new OfferResource($this->whenLoaded('offer')),
            'status' => new StatusResource($this->whenLoaded('status')),
        ];
    }
}
