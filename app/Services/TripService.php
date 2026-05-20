<?php

namespace App\Services;

use App\Notifications\TripBookingNotification;
use App\Notifications\UserActivityNotification;
use App\Repositories\TripRepository;
use App\Traits\ApiResponse;

class TripService
{
    protected $tripRepo;
    use ApiResponse;

    public function __construct(TripRepository $tripRepo)
    {
        $this->tripRepo = $tripRepo;
    }


    public function list($perPage, $filters = [])
    {
        $trips = $this->tripRepo->all($perPage, $filters);

        $authUser = auth()->user();

        $trips->getCollection()->transform(function ($trip) use ($authUser) {

            $match = $this->calculateMatch($authUser, $trip->publisher);

            $trip->match_percentage = $match['percentage'];
            $trip->matches = $match['matches'];

            return $trip;
        });

        return $trips;
    }

    public function create($data)
    {


        $data['total_seat'] = $data['available_seat'];
        $trip = $this->tripRepo->create($data);

        $trip->publisher?->notify(new UserActivityNotification(
            'trip_created',
            'Trip created',
            $this->tripMessage('Your trip was created.', $trip),
            $this->tripNotificationData($trip)
        ));

        return $trip;
    }

    public function show($id)
    {
        return $this->tripRepo->find($id);
    }

    public function update($id, $data)
    {
        $trip = $this->ownedTripOrError($id);

        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }

        $trip = $this->tripRepo->update($id, $data);

        $this->notifyTripUsers(
            $trip,
            'trip_updated',
            'Trip updated',
            $this->tripMessage('A trip you are connected with was updated.', $trip)
        );

        return $trip;
    }

    public function updateMyTripDetails($id, array $data)
    {
        $trip = $this->ownedTripOrError($id);

        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }

        $trip = $this->tripRepo->update($id, $data);

        $this->notifyTripUsers(
            $trip,
            'trip_details_updated',
            'Trip details updated',
            $this->tripMessage('A trip you are connected with was updated.', $trip)
        );

        return $trip;
    }

    public function delete($id)
    {
        $trip = $this->ownedTripOrError($id);

        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }

        $this->notifyTripUsers(
            $trip,
            'trip_deleted',
            'Trip deleted',
            $this->tripMessage('A trip you are connected with was deleted.', $trip)
        );

        return $this->tripRepo->delete($id);
    }

    public function complete($id)
    {
        $trip = $this->ownedTripOrError($id);

        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }

        $trip->update([
            'ride_status' => 'completed'
        ]);

        $this->notifyTripUsers(
            $trip,
            'trip_completed',
            'Trip completed',
            $this->tripMessage('A trip you are connected with was completed.', $trip)
        );

        return $trip;
    }
    public function cancel($id)
    {
        $trip = $this->ownedTripOrError($id);

        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }

        $trip->update([
            'ride_status' => 'cancelled'
        ]);

        $this->notifyTripUsers(
            $trip,
            'trip_cancelled',
            'Trip cancelled',
            $this->tripMessage('A trip you are connected with was cancelled.', $trip)
        );

        return $trip;
    }

    public function calculateMatch($authUser, $publisher)
    {
        if (!$authUser || !$publisher) {
            return [
                'percentage' => 0,
                'matches' => []
            ];
        }

        $matches = [];
        $score = 0;
        // dd($authUser, $publisher);
        /*
        |--------------------------------------------------------------------------
        | 1. ROUTE COMPATIBILITY (70%)
        |--------------------------------------------------------------------------
        | Assume you already calculate route match somewhere
        | Example: $routeMatchPercent = 0–100
        */
        $routeMatchPercent = $this->calculateRouteMatch($authUser, $publisher); // return 0–100
        $routeScore = ($routeMatchPercent / 100) * 70;

        if ($routeMatchPercent > 0) {
            $matches[] = 'route_match';
        }

        /*
        |--------------------------------------------------------------------------
        | 2. VIBE (20%)
        |--------------------------------------------------------------------------
        | music_preference + conversation_level + ride_style
        */
        $vibeScore = 0;
        $vibeTotal = 3;

        // 🎵 MUSIC (enum, not array)
        if (
            $authUser->music_preference &&
            $authUser->music_preference === $publisher->music_preference
        ) {
            $vibeScore++;
            $matches[] = $authUser->music_preference;
        }

        // 💬 CONVERSATION
        if (
            $authUser->conversation_level &&
            $authUser->conversation_level === $publisher->conversation_level
        ) {
            $vibeScore++;
            $matches[] = $authUser->conversation_level;
        }

        // 🚗 RIDE STYLE
        if (
            $authUser->ride_style &&
            $authUser->ride_style === $publisher->ride_style
        ) {
            $vibeScore++;
            $matches[] = $authUser->ride_style;
        }

        $vibeFinal = ($vibeScore / $vibeTotal) * 20;

        /*
        |--------------------------------------------------------------------------
        | 3. PREFERENCES (10%)
        |--------------------------------------------------------------------------
        | smoke, pet, etc.
        */
        $prefScore = 0;
        $prefTotal = 2;

        // 🚬 SMOKE
        if (
            $authUser->smoke &&
            $authUser->smoke === $publisher->smoke
        ) {
            $prefScore++;
            $matches[] = 'smoke_' . $authUser->smoke;
        }

        // 🐶 PET
        if (
            $authUser->pet &&
            $authUser->pet === $publisher->pet
        ) {
            $prefScore++;
            $matches[] = 'pet_' . $authUser->pet;
        }

        $prefFinal = ($prefScore / $prefTotal) * 10;

        /*
        |--------------------------------------------------------------------------
        | FINAL SCORE
        |--------------------------------------------------------------------------
        */
        $score = $routeScore + $vibeFinal + $prefFinal;

        return [
            'percentage' => round($score),
            'matches' => array_values(array_unique($matches))
        ];
    }

    private function calculateRouteMatch($authUser, $publisher)
    {
        // Example logic (replace with real geo logic)
        if ($authUser->what_kind_ride === $publisher->what_kind_ride) {
            return 100;
        }

        return 0;
    }

    public function saveBooking($request, $id)
    {

        $trip = $this->tripRepo->find($id);

        if ($trip->available_seat < $request->seat_count) {
            return $this->errorResponse('Not enough seats available');
        }

        $totalPrice = $trip->price_per_seat * $request->seat_count;

        $trip = $this->tripRepo->booking($trip, $request, $totalPrice);

        $trip->trip->publisher?->notify(new TripBookingNotification($trip, 'booking_requested'));
        $trip->user?->notify(new TripBookingNotification($trip, 'booking_created'));

        return $trip;
    }

    public function tripbooking($request)
    {
        $trip = $this->tripRepo->mytrips($request, auth()->id());
        return $trip;
    }

    public function joinedTrips($request)
    {
        return $this->tripRepo->joinedTrips($request, auth()->id());
    }


    public function tripusers($request, $id)
    {
        $trip = $this->tripRepo->find($id);

        if (!$trip) {
            return $this->errorResponse('Trip Not found');
        }
        if ($trip->publisher_id != auth()->id()) {
            return $this->errorResponse('Trip Not found');
        }

        $users = $this->tripRepo->mytripUsers($request, $trip->id);

        return $users;
    }

    public function triprequest($status, $id)
    {
        $tripbooking = $this->tripRepo->tripbooking($id);

        if (!$tripbooking) {
            return $this->errorResponse('Booking Not found');
        }

        if ($tripbooking->trip?->publisher_id !== auth()->id()) {
            return $this->errorResponse('Booking Not found', 404);
        }

        if ($tripbooking->status === $status) {
            return $tripbooking;
        }

        if ($status === 'approved' && $tripbooking->trip->available_seat < $tripbooking->seat_count) {
            return $this->errorResponse('Not enough seats available', 400);
        }

        if ($status == 'approved') {
            $avlb_seat = $tripbooking->trip->available_seat - $tripbooking->seat_count;
        } elseif ($status == 'rejected' && $tripbooking->status == 'approved') {
            $avlb_seat = $tripbooking->trip->available_seat + $tripbooking->seat_count;
        } else {
            $avlb_seat = $tripbooking->trip->available_seat;
        }

        $tripbooking->update(['status' => $status]);
        $tripbooking->trip->update(['available_seat' => $avlb_seat]);

        $notificationType = $status === 'approved' ? 'booking_approved' : 'booking_rejected';
        $tripbooking->user?->notify(new TripBookingNotification($tripbooking, $notificationType));
        $tripbooking->trip->publisher?->notify(new UserActivityNotification(
            $status === 'approved' ? 'booking_approval_sent' : 'booking_rejection_sent',
            $status === 'approved' ? 'Booking approved' : 'Booking rejected',
            $status === 'approved'
                ? $this->tripMessage('You approved a booking request for', $tripbooking->trip)
                : $this->tripMessage('You rejected a booking request for', $tripbooking->trip),
            array_merge($this->tripNotificationData($tripbooking->trip), [
                'booking_id' => $tripbooking->id,
                'requester_id' => $tripbooking->user_id,
                'status' => $tripbooking->status,
            ])
        ));

        return $tripbooking;
    }

    private function ownedTripOrError($id)
    {
        $trip = $this->tripRepo->find($id);

        if ($trip->publisher_id !== auth()->id()) {
            return $this->errorResponse('Trip not found', 404);
        }

        return $trip;
    }

    private function notifyTripUsers($trip, string $eventType, string $title, string $message): void
    {
        $trip->loadMissing(['publisher', 'bookings.user']);

        collect([$trip->publisher])
            ->merge($trip->bookings->pluck('user'))
            ->filter()
            ->unique('id')
            ->each(function ($user) use ($eventType, $title, $message, $trip) {
                $user->notify(new UserActivityNotification(
                    $eventType,
                    $title,
                    $message,
                    $this->tripNotificationData($trip)
                ));
            });
    }

    private function tripMessage(string $prefix, $trip): string
    {
        return trim($prefix . ' ' . $this->tripRoute($trip));
    }

    private function tripRoute($trip): string
    {
        $route = trim(($trip?->from_location ?? '') . ' to ' . ($trip?->to_location ?? ''));

        return $route ?: 'Trip details are available in the app.';
    }

    private function tripNotificationData($trip): array
    {
        return [
            'trip_id' => $trip?->id,
            'publisher_id' => $trip?->publisher_id,
            'from_location' => $trip?->from_location,
            'to_location' => $trip?->to_location,
            'ride_date' => $trip?->ride_date,
            'ride_time' => $trip?->ride_time,
            'ride_status' => $trip?->ride_status,
        ];
    }
}
