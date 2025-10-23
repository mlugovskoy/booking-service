<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateCsvReport extends Command
{
    protected $signature = 'report:service';

    protected $description = 'Generate CSV report';

    public function handle(): void
    {
        $csv = fopen('php://temp', 'r+');

        fputcsv($csv, ['ID', 'ID услуги', 'Имя клиента', 'Номер телефона клиента', 'Начало брони'], ';');

        Booking::query()
            ->select('id', 'service_id', 'client_name', 'client_phone', 'booking_start_time')
            ->chunk(500, function ($bookings) use ($csv) {
                foreach ($bookings as $booking) {
                    fputcsv(
                        $csv,
                        [
                            $booking->id,
                            $booking->service_id,
                            $booking->client_name,
                            $booking->client_phone,
                            $booking->booking_start_time
                        ],
                        ';'
                    );
                }
            });

        rewind($csv);
        $content = "\xEF\xBB\xBF" . stream_get_contents($csv);
        fclose($csv);

        $hashStr = Str::random();

        Storage::put('reports/service' . $hashStr . '.csv', $content);

        $this->info('CSV-отчёт успешно создан: storage/app/reports/service' . $hashStr . '.csv');
    }
}
