<?php

namespace App\Services;

use App\Repositories\BuildingRepository;
use Illuminate\Support\Collection;

class BuildingService
{
    public function __construct(private readonly BuildingRepository $buildingRepository) {}

    public function getAll(): Collection
    {
        return $this->buildingRepository->all();
    }
}