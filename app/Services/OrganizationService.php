<?php

namespace App\Services;

use App\Repositories\OrganizationRepository;
use App\Services\ActivityService;
use Illuminate\Support\Collection;

class OrganizationService
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository,
        private readonly ActivityService $activityService
    ) {}

    public function getByBuilding(int $buildingId): Collection
    {
        return $this->organizationRepository->getByBuilding($buildingId);
    }

    public function getByActivity(int $activityId): Collection
    {
        $ids = $this->activityService->getActivityWithChildren($activityId);
        return $this->organizationRepository->getByActivities($ids);
    }

    public function getByRadius(float $lat, float $lng, float $radius): Collection
    {
        return $this->organizationRepository->getByRadius($lat, $lng, $radius);
    }

    public function findById(int $id)
    {
        return $this->organizationRepository->findById($id);
    }

    public function searchByName(string $name): Collection
    {
        return $this->organizationRepository->searchByName($name);
    }
}