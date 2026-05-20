<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
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
            'from_location' => 'sometimes|string|max:255',
            'from_latitude' => 'sometimes|numeric|between:-90,90',
            'from_longitude' => 'sometimes|numeric|between:-180,180',

            'to_location' => 'sometimes|string|max:255',
            'to_latitude' => 'sometimes|numeric|between:-90,90',
            'to_longitude' => 'sometimes|numeric|between:-180,180',

            'ride_date' => 'sometimes|date',
            'ride_time' => 'sometimes|date_format:H:i',
            "car_name" => 'required',
            "color" => 'required',

            'available_seat' => 'sometimes|integer|min:1',
            'price_per_seat' => 'sometimes|numeric|min:0',

            'ride_status' => 'sometimes|in:active,completed,cancelled',
        ];
    }

    public function messages()
    {
        return [
            'from_latitude.between' => 'Latitude must be between -90 and 90.',
            'from_longitude.between' => 'Longitude must be between -180 and 180.',
            'to_latitude.between' => 'Latitude must be between -90 and 90.',
            'to_longitude.between' => 'Longitude must be between -180 and 180.',
        ];
    }
}
