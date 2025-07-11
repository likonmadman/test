<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="resource_id", type="integer", example=2),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-07-11T09:00:00Z"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-07-11T10:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'resource_id',
        'user_id',
        'start_time',
        'end_time',
    ];

    /**
     * Связь с ресурсом.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Связь с пользователем.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
