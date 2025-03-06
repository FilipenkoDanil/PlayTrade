<?php

namespace App\Http\Resources;

use App\Http\Resources\Deal\DealInfoResource;
use App\Http\Resources\User\UserProfileResource;
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
            'created_at' => $this->created_at->diffForHumans(),
            'deal' => new DealInfoResource($this->whenLoaded('deal')),
            'user' => new UserProfileResource($this->whenLoaded('user')),
        ];
    }
}
