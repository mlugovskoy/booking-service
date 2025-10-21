<?php

namespace App\Repository\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

interface BookingRepositoryContract
{
    public function findByServiceIdBooking(string|int $serviceId, Carbon $date): Collection;
}
