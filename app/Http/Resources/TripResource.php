<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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

            'from' => [
                'location' => $this->from_location,
                'lat' => $this->from_latitude,
                'lng' => $this->from_longitude,
            ],

            'to' => [
                'location' => $this->to_location,
                'lat' => $this->to_latitude,
                'lng' => $this->to_longitude,
            ],

            'ride_date' => $this->ride_date,
            'ride_time' => $this->ride_time,

            'available_seat' => $this->available_seat,
            'price_per_seat' => $this->price_per_seat,
            'status' => $this->ride_status,
            'match_percentage' => $this->match_percentage ?? 0,

            'matches' => $this->matches ?? [],

            'publisher' => $this->user ?? null,
        ];
    }
}
