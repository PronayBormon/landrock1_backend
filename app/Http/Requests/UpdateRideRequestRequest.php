<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRideRequestRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_location'  => 'sometimes|required|string|max:255',
            'from_latitude'  => 'nullable|numeric',
            'from_longitude' => 'nullable|numeric',
            'to_location'    => 'sometimes|required|string|max:255',
            'to_latitude'    => 'nullable|numeric',
            'to_longitude'   => 'nullable|numeric',
            'travel_date'    => 'sometimes|required|date',
            'preferred_time' => 'nullable|string|max:100',
            'note'           => 'nullable|string|max:300',
            'seats_needed'   => 'sometimes|integer|min:1|max:10',
            'status'         => 'sometimes|in:active,pending,completed,cancelled',
        ];
    }
}
