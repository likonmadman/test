<?php

namespace App\Services;

use App\Repositories\ActivityRepository;

class ActivityService
{
    public function __construct(private readonly ActivityRepository $activityRepository) {}

    public function getActivityWithChildren(int $activityId): array
    {
        $activity = $this->activityRepository->findByIdWithChildren($activityId);
        return $this->collectIds($activity);
    }

    private function collectIds($activity): array
    {
        $ids = [$activity->id];
        foreach ($activity->children as $child) {
            $ids = array_merge($ids, $this->collectIds($child));
        }
        return $ids;
    }
}