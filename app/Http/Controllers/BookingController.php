<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingSlotsRequest;
use App\Models\Service;
use App\Repository\Contracts\ServiceRepositoryContract;
use App\Service\Contracts\BookingServiceContract;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Inertia\Response;
use Inertia\ResponseFactory;

class BookingController extends Controller
{
    public function __construct(
        private ServiceRepositoryContract $serviceRepository,
        private BookingServiceContract $bookingService
    ) {
    }

    public function index(): Response|ResponseFactory
    {
        return inertia('Booking/Index', ['services' => $this->serviceRepository->getAllServices()]);
    }

    public function getSlots(BookingSlotsRequest $request): JsonResponse
    {
        $service = $this->serviceRepository->findService($request->service_id);
        $startDate = Carbon::parse($request->date)->startOfDay();

        if ($this->bookingService->checkSundayDay($startDate)) {
            return response()->json(['slots' => []]);
        }

        return response()->json(['slots' => $this->bookingService->getSlots($service, $startDate)]);
    }

    /**
     * @throws ErrorException
     */
    public function store(BookingRequest $request): void
    {
        $service = Service::query()->findOrFail($request->service_id);
        $startTime = Carbon::parse($request->booking_start_time);
        $endTime = $startTime->copy()->addMinutes($service->duration + 30);
        $clientInfo = [
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone
        ];

        $this->bookingService->store($service, $startTime, $endTime, $clientInfo);
    }
}
