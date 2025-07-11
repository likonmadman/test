<?php

namespace App\Repositories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

class ResourceRepository
{
    public function all(): Collection
    {
        return Resource::all();
    }

    public function create(array $data): Resource
    {
        return Resource::create($data);
    }

    public function findById(int $id): Resource
    {
        return Resource::findOrFail($id);
    }
}