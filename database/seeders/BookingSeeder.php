<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    protected array $bookingList = [
        ['service_id' => 1, 'booking_start_time' => '2025-10-16 13:00:00'],
        ['service_id' => 1, 'booking_start_time' => '2025-10-16 16:00:00'],
        ['service_id' => 1, 'booking_start_time' => '2025-10-17 11:00:00'],
        ['service_id' => 1, 'booking_start_time' => '2025-10-17 11:00:00'],
        ['service_id' => 1, 'booking_start_time' => '2025-10-17 13:00:00'],
        ['service_id' => 1, 'booking_start_time' => '2025-10-17 18:00:00'],

        ['service_id' => 2, 'booking_start_time' => '2025-10-16 11:30:00'],
        ['service_id' => 2, 'booking_start_time' => '2025-10-16 18:30:00'],

        ['service_id' => 3, 'booking_start_time' => '2025-10-16 10:00:00'],
        ['service_id' => 3, 'booking_start_time' => '2025-10-16 11:30:00'],
        ['service_id' => 3, 'booking_start_time' => '2025-10-16 18:30:00'],

        ['service_id' => 4, 'booking_start_time' => '2025-10-17 14:00:00']
    ];

    public function run(): void
    {
        foreach ($this->bookingList as $booking) {
            Booking::query()->create([
                'service_id' => $booking['service_id'],
                'client_name' => 'Тестовое имя',
                'client_phone' => '555555',
                'booking_start_time' => $booking['booking_start_time']
            ]);
        }
    }
}
