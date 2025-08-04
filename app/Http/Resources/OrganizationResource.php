<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phones' => $this->phones->pluck('phone'),
            'building' => new BuildingResource($this->whenLoaded('building')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}