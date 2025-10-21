<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|int|exists:services,id',
            'booking_start_time' => 'required|date'
        ];
    }
}
