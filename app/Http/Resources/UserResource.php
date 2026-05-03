<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'phone' => $this->phone,
            'phone_is_verified' => $this->phone_verified_at ? true : false,
            'ride_style' => $this->ride_style,
            'music_preference' => $this->music_preference,
            'conversation_level' => $this->conversation_level,
            'interested' => $this->interested ?? [],
            'personalization' => $this->personalization ?? [],
            'smoke' => $this->smoke,
            'pet' => $this->pet,
            'connect_like_rider' => $this->connect_like_rider,
            'what_kind_ride' => $this->what_kind_ride,
            'avg_review' => $this->avg_review,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
