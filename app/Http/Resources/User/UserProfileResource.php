<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'name' => $this->name,
            'created_at' => $this->created_at->diffForHumans(),
            'offers' => $this->whenLoaded('activeOffers'),
            'is_online' => $this->isOnline(),
            'last_activity_at' => $this->last_activity_at->diffForHumans(),
        ];
    }
}
