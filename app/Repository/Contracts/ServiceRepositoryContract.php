<?php

namespace App\Repository\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryContract
{
    public function getAllServices(): Collection;

    public function findService(string|int $id);
}
