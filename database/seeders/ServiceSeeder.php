<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::query()->create([
            'name' => 'Поездка на квадроцикле',
            'duration' => 30
        ]);
        Service::query()->create([
            'name' => 'Поездка на квадроцикле',
            'duration' => 60
        ]);
        Service::query()->create([
            'name' => 'Тур на эндуро',
            'duration' => 60
        ]);
        Service::query()->create([
            'name' => 'Тур на эндуро',
            'duration' => 120
        ]);
    }
}
