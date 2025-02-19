<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'deal_id' => $this->deal_id,
            'user_id' => $this->user_id,
            'deal' => new DealResource($this->whenLoaded('deal')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
