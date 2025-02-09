<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'description' => $this->description,
            'game_id' => $this->game_id,
            'unit_id' => $this->unit_id,
            'game' => new GameResource($this->whenLoaded('game')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
            'servers' => ServerResource::collection($this->whenLoaded('servers')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
        ];
    }
}
