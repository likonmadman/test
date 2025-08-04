<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
 *     @OA\Property(property="phones", type="array", @OA\Items(type="string", example="8-923-666-13-13")),
 *     @OA\Property(property="building", type="string", example="г. Москва, ул. Ленина 1"),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(type="string", example="Молочная продукция")
 *     )
 * )
 */
class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building_id',
    ];

    /**
     * Организация принадлежит конкретному зданию
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * У организации может быть несколько телефонов
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * Организация может заниматься несколькими видами деятельности
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'organization_activity')
            ->using(OrganizationActivity::class);
    }
}