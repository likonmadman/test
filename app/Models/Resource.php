<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Resource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Room 101"),
 *     @OA\Property(property="type", type="string", example="room"),
 *     @OA\Property(property="description", type="string", example="Large conference room"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Resource extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    /**
     * Связь с бронированиями.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
