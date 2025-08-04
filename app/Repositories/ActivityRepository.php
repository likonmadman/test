<?php

namespace App\Repositories;

use App\Models\Activity;

class ActivityRepository
{
    public function findByIdWithChildren(int $id): Activity
    {
        return Activity::with('children.children.children')->findOrFail($id);
    }
}