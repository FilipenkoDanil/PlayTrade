<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
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
            'card_number' => $this->card_number,
            'fee_percentage' => $this->fee_percentage,
            'requested_amount' => $this->requested_amount,
            'payout_amount' => $this->payout_amount,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
            'user' => UserProfileResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
