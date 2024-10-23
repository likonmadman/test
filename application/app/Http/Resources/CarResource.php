<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'Id' => $this->Id,
            'RegNumber' => $this->RegNumber,
            'Vin' => $this->Vin,
            'Model' => $this->Model,
            'Brand' => $this->Brand,
        ];
    }
}
