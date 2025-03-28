<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'servers' => ServerResource::collection($this->whenLoaded('servers')),
        ];
    }
}
