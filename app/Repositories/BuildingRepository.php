<?php

namespace App\Repositories;

use App\Models\Building;
use Illuminate\Support\Collection;

class BuildingRepository
{
    public function all(): Collection
    {
        return Building::with('organizations')->get();
    }
}