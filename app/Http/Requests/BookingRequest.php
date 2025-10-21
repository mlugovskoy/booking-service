<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|int|exists:services,id',
            'booking_start_time' => 'required|date',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20'
        ];
    }
}
