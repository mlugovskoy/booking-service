<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\Service;
use App\Repository\Contracts\BookingRepositoryContract;
use App\Service\Contracts\BookingServiceContract;
use Carbon\CarbonInterface;
use ErrorException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingService implements BookingServiceContract
{
    public function __construct(
        private BookingRepositoryContract $bookingRepository
    ) {
    }

    public function getSlots(Service $service, Carbon $startDate): array
    {
        $bookings = $this->bookingRepository->findByServiceIdBooking($service->id, $startDate);

        $busyIntervals = $this->buildBusyIntervals($bookings, $service->duration);

        return $this->buildSlots($startDate, $busyIntervals, $service->duration);
    }

    /**
     * @throws ErrorException
     */
    public function store(Service $service, Carbon $startTime, Carbon $endTime, array $clientInfo): void
    {
        if ($this->checkSundayDay($startTime)) {
            throw new ErrorException('Бронирование в воскресенье недоступно');
        }

        if ($endTime->gt(Carbon::createFromTime(20, 0, 0, $startTime->timezone))) {
            throw new ErrorException('Бронирование доступно только с 10:00 до 20:00');
        }

        DB::transaction(function () use ($service, $startTime, $clientInfo) {
            $existsBookings = Booking::query()
                ->where('service_id', $service->id)
                ->where('booking_start_time', '>', $startTime)
                ->where('booking_start_time', '<', $startTime->copy()->addMinutes($service->duration + 30))
                ->lockForUpdate()
                ->exists();

            if ($existsBookings) {
                throw new ErrorException('Время занято (конфликт при одновременном бронировании)');
            }

            Booking::query()
                ->create([
                    'service_id' => $service->id,
                    'client_name' => $clientInfo['client_name'],
                    'client_phone' => $clientInfo['client_phone'],
                    'booking_start_time' => $startTime,
                ]);
        });
    }


    public function checkSundayDay($date): bool
    {
        return $date->dayOfWeek === CarbonInterface::SUNDAY;
    }

    protected function buildBusyIntervals(Collection $bookings, int $serviceDuration = 0): array
    {
        $busyDates = [];

        foreach ($bookings as $date) {
            $start = Carbon::parse($date->booking_start_time);
            $end = $start->copy()->addMinutes($serviceDuration + 30);
            $busyDates[] = ['start' => $start, 'end' => $end];
        }

        return $busyDates;
    }

    protected function buildSlots(Carbon $startDate, array $busyIntervals, int $serviceDuration = 0): array
    {
        $slots = [];
        $current = Carbon::createFromTime(10)->setDateFrom($startDate);
        $endOfDay = Carbon::createFromTime(20)->setDateFrom($startDate);

        while ($current->lt($endOfDay)) {
            $slotEnd = $current->copy()->addMinutes($serviceDuration + 30);

            if ($slotEnd->gt($endOfDay)) {
                break;
            }

            $isFree = true;
            foreach ($busyIntervals as $date) {
                if ($current->lt($date['end']) && $slotEnd->gt($date['start'])) {
                    $isFree = false;
                    break;
                }
            }

            if ($isFree) {
                $slots[] = $current->format('Y-m-d H:i:s');
            }

            $current->addMinutes(30);
        }

        return $slots;
    }
}
