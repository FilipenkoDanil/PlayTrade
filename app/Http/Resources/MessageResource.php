<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'type' => $this->type,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => UserProfileResource::make($this->whenLoaded('user')),
        ];
    }
}
