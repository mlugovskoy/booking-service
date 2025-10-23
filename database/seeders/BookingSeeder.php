<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $total = 500_000;
        $chunkSize = 5_000;

        $data = [];
        $baseTime = Carbon::now();

        for ($i = 0; $i < $total; $i++) {
            $bookingTime = $baseTime->copy()->addMinutes($i * 10);

            $data[] = [
                'service_id' => rand(1, 4),
                'client_name' => 'Тестовое имя ' . $i,
                'client_phone' => '555555',
                'booking_start_time' => $bookingTime->toDateTimeString(),
                'created_at' => $bookingTime->toDateTimeString(),
                'updated_at' => $bookingTime->toDateTimeString(),
            ];

            if (count($data) === $chunkSize) {
                DB::table('bookings')->insert($data);
                $data = [];
                gc_collect_cycles();
            }
        }

        if (!empty($data)) {
            DB::table('bookings')->insert($data);
        }

        $this->command->info("Добавлено {$total} записей в таблицу bookings");
    }
}
