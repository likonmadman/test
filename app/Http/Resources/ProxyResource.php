<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Proxy
 */
class ProxyResource extends JsonResource
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
            'host' => $this->host,
            'port' => $this->port,
            'protocol' => $this->protocol->value,
            'username' => $this->username,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'latency_ms' => $this->latency_ms,
            'last_checked_at' => $this->last_checked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
