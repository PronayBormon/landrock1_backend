<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $seats = (int) ($this->seats_needed ?? 1);

        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'from_location'  => $this->from_location,
            'to_location'    => $this->to_location,
            'from' => [
                'location' => $this->from_location,
                'lat'      => $this->from_latitude,
                'lng'      => $this->from_longitude,
            ],
            'to' => [
                'location' => $this->to_location,
                'lat'      => $this->to_latitude,
                'lng'      => $this->to_longitude,
            ],
            'travel_date'           => $this->travel_date ? $this->travel_date->format('Y-m-d') : null,
            'travel_date_formatted' => $this->travel_date ? $this->travel_date->format('l, d F Y') : null,
            'preferred_time'        => $this->preferred_time ?? 'Anytime',
            'note'                  => $this->note,
            'seats_needed'          => $seats,
            'seats_text'            => $seats . ' ' . ($seats === 1 ? 'Seat' : 'Seats'),
            'status'                => $this->status,
            'created_at_human'      => $this->created_at ? $this->created_at->diffForHumans() : null,
            'user'                  => $this->whenLoaded('user', function () {
                $avatarUrl = User::resolveAvatarUrl($this->user->getRawOriginal('avatar'));
                return [
                    'id'            => $this->user->id,
                    'name'          => $this->user->name,
                    'email'         => $this->user->email,
                    'avatar'        => !empty($avatarUrl) ? $avatarUrl : asset('/backend/assets/images/user.webp'),
                    'rating'        => (float) ($this->user->avg_review ?? 0),
                    'phone'         => $this->user->phone,
                ];
            }),
            'created_at'     => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at'     => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
