<?php

namespace App\Providers;

use App\Repository\BookingRepository;
use App\Repository\Contracts\BookingRepositoryContract;
use App\Repository\Contracts\ServiceRepositoryContract;
use App\Repository\ServiceRepository;
use App\Service\BookingService;
use App\Service\Contracts\BookingServiceContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServiceRepositoryContract::class, ServiceRepository::class);
        $this->app->bind(BookingRepositoryContract::class, BookingRepository::class);
        $this->app->bind(BookingServiceContract::class, BookingService::class);
    }

    public function boot(): void
    {
    }
}
