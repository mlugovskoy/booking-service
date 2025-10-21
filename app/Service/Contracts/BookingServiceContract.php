<?php

namespace App\Service\Contracts;

use App\Models\Service;
use Illuminate\Support\Carbon;

interface BookingServiceContract
{
    public function getSlots(Service $service, Carbon $startDate): array;

    public function store(Service $service, Carbon $startTime, Carbon $endTime, array $clientInfo): void;
}
