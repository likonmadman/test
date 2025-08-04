<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Building",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="address", type="string", example="г. Москва, ул. Ленина 1, офис 3"),
 *     @OA\Property(property="latitude", type="number", format="float", example=55.7558),
 *     @OA\Property(property="longitude", type="number", format="float", example=37.6173)
 * )
 */
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Здания могут содержать несколько организаций
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}