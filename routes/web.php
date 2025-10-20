<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking/slots', [BookingController::class, 'getSlots'])->name('booking.slots');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
