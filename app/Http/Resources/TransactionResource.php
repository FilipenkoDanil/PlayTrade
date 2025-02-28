<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'transactable_type' => $this->transactable_type,
            'transactable_id' => $this->transactable_id,
            'transactable' => $this->transactable,
            'created_at' => $this->created_at
        ];
    }
}
