<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_location' => 'required|string',
            'from_latitude' => 'required|numeric',
            'from_longitude' => 'required|numeric',

            'to_location' => 'required|string',
            'to_latitude' => 'required|numeric',
            'to_longitude' => 'required|numeric',

            'ride_date' => 'required|date',
            'ride_time' => 'required',

            'available_seat' => 'required|integer|min:1',
            'price_per_seat' => 'required|numeric|min:0'
        ];
    }
}
