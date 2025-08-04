<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Activity",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Розничная торговля"),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         description="Подвиды деятельности",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Торговля продуктами питания")
 *         )
 *     )
 * )
 */
class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * Родительская деятельность
     */
    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * Дочерние виды деятельности
     */
    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * Организации, относящиеся к этому виду деятельности
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_activity')
            ->using(OrganizationActivity::class);
    }
}