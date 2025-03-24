<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'amount' => $this->amount,
            'price' => $this->price,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id,
            'seller_id' => $this->seller_id,
            'server_id' => $this->server_id,
            'server' => new ServerResource($this->whenLoaded('server')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'seller' => new UserProfileResource($this->whenLoaded('seller')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
        ];

        if (Auth::id() == $this->seller_id) {
            $array['auto_message'] = $this->auto_message;
        }

        return $array;
    }
}
