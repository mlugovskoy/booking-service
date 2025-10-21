<?php

namespace App\Repository;

use App\Models\Service;
use App\Repository\Contracts\ServiceRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryContract
{
    public function getAllServices(): Collection
    {
        return Service::all();
    }

    public function findService(string|int $id)
    {
        return Service::query()->findOrFail($id);
    }
}
