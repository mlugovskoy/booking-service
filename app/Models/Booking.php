<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'service_id',
        'booking_start_time',
        'client_name',
        'client_phone',
    ];
}
