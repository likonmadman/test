<?php

namespace App\Services;

use App\Enums\ProxyStatus;

readonly class ProxyCheckResult
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ProxyStatus $status,
        public ?int $latencyMs = null,
    ) {
    }
}
