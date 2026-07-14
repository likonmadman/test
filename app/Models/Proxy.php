<?php

namespace App\Models;

use App\Enums\ProxyProtocol;
use App\Enums\ProxyStatus;
use App\Services\ProxyCheckResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    /** @use HasFactory<\Database\Factories\ProxyFactory> */
    use HasFactory;

    protected $fillable = [
        'host',
        'port',
        'protocol',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'status' => 'unknown',
    ];

    protected function casts(): array
    {
        return [
            'protocol' => ProxyProtocol::class,
            'status' => ProxyStatus::class,
            'port' => 'integer',
            'latency_ms' => 'integer',
            'last_checked_at' => 'datetime',
            'password' => 'encrypted',
        ];
    }

    /**
     * Запись результата проверки в модель
     */
    public function applyCheckResult(ProxyCheckResult $result): void
    {
        $this->forceFill([
            'status' => $result->status,
            'latency_ms' => $result->latencyMs,
            'last_checked_at' => now(),
        ])->save();
    }
}
