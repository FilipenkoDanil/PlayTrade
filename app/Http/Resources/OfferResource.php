<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'title' => $this->title,
            'amount' => $this->amount,
            'price' => $this->price,
            'description' => $this->description,
            'auto_message' => $this->auto_message,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id,
            'seller_id' => $this->seller_id,
            'server_id' => $this->server_id,
            'server' => new ServerResource($this->whenLoaded('server')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'seller' => new UserProfileResource($this->whenLoaded('seller')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
        ];
    }
}
