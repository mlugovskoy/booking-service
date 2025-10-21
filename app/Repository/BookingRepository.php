<?php

namespace App\Repository;

use App\Models\Booking;
use App\Repository\Contracts\BookingRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class BookingRepository implements BookingRepositoryContract
{
    public function findByServiceIdBooking(string|int $serviceId, Carbon $date): Collection
    {
        return Booking::query()
            ->where('service_id', $serviceId)
            ->whereDate('booking_start_time', $date)
            ->get();
    }
}
