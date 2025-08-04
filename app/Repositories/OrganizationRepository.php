<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Support\Collection;

class OrganizationRepository
{
    public function getByBuilding(int $buildingId): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->where('building_id', $buildingId)
            ->get();
    }

    public function getByActivities(array $activityIds): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->whereHas('activities', fn($q) => $q->whereIn('activities.id', $activityIds))
            ->get();
    }

    public function getByRadius(float $lat, float $lng, float $radius): Collection
    {
        return Organization::whereHas('building', function ($query) use ($lat, $lng, $radius) {
            $query->whereRaw("
            (6371 * acos(
                cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude))
            )) <= ?
        ", [$lat, $lng, $lat, $radius]);
        })->get();
    }

    public function findById(int $id): ?Organization
    {
        return Organization::with(['phones', 'activities', 'building'])->find($id);
    }

    public function searchByName(string $name): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->where('name', 'LIKE', "%$name%")
            ->get();
    }
}