<?php

namespace App\Services;

use App\Repositories\ResourceRepository;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

class ResourceService
{
    public function __construct(protected ResourceRepository $repository) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function create(array $data): Resource
    {
        return $this->repository->create($data);
    }
}