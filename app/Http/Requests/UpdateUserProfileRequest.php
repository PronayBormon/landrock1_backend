<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'ride_style' => 'nullable|string',
            'music_preference' => 'nullable|string',
            'conversation_level' => 'nullable|string',
            'interested' => 'nullable|array',
            'interested.*' => 'string',
            'personalization' => 'nullable|array',
            'personalization.*' => 'string',
            'smoke' => 'nullable|in:yes,no',
            'pet' => 'nullable|string',
            'connect_like_rider' => 'nullable|string',
            'what_kind_ride' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'avatar.image' => 'Avatar must be an image',
        ];
    }
}
