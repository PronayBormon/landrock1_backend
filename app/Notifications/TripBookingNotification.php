<?php

namespace App\Notifications;

use App\Models\TripBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripBookingNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected TripBooking $booking,
        protected string $eventType
    ) {
        $this->booking->loadMissing(['trip.publisher', 'user']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $trip = $this->booking->trip;
        $requester = $this->booking->user;

        return [
            'type' => $this->eventType,
            'title' => $this->title(),
            'message' => $this->message(),
            'booking_id' => $this->booking->id,
            'trip_id' => $this->booking->trip_id,
            'status' => $this->booking->status,
            'seat_count' => $this->booking->seat_count,
            'total_price' => $this->booking->total_price,
            'requester_id' => $requester?->id,
            'requester_name' => $requester?->name,
            'publisher_id' => $trip?->publisher_id,
            'from_location' => $trip?->from_location,
            'to_location' => $trip?->to_location,
            'ride_date' => $trip?->ride_date,
            'ride_time' => $trip?->ride_time,
        ];
    }

    protected function title(): string
    {
        return match ($this->eventType) {
            'booking_requested' => 'New trip booking request',
            'booking_created' => 'Booking request sent',
            'booking_approved' => 'Trip booking approved',
            'booking_rejected' => 'Trip booking rejected',
            default => 'Trip booking update',
        };
    }

    protected function message(): string
    {
        $requesterName = $this->booking->user?->name ?? 'A user';
        $route = $this->routeLabel();

        return match ($this->eventType) {
            'booking_requested' => "{$requesterName} requested {$this->booking->seat_count} seat(s) for your trip {$route}.",
            'booking_created' => "Your booking request for {$route} was sent.",
            'booking_approved' => "Your booking request for {$route} was approved.",
            'booking_rejected' => "Your booking request for {$route} was rejected.",
            default => "Your trip booking for {$route} was updated.",
        };
    }

    protected function routeLabel(): string
    {
        $trip = $this->booking->trip;
        $route = trim(($trip?->from_location ?? '') . ' to ' . ($trip?->to_location ?? ''));

        return $route !== 'to' ? $route : 'this trip';
    }
}
