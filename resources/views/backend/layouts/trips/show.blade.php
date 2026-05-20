@extends('backend.master')

@push('title')
    Trip Details
@endpush

@section('content')
    <div class="page-container">

        <div class="row">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- TRIP DETAILS --}}
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Trip Details
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    From Location
                                </label>

                                <div>
                                    {{ $trip->from_location }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    To Location
                                </label>

                                <div>
                                    {{ $trip->to_location }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Ride Date
                                </label>

                                <div>
                                    {{ \Carbon\Carbon::parse($trip->ride_date)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Ride Time
                                </label>

                                <div>
                                    {{ \Carbon\Carbon::parse($trip->ride_time)->format('h:i A') }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Available Seats
                                </label>

                                <div>
                                    {{ $trip->available_seat }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Total Seats
                                </label>

                                <div>
                                    {{ $trip->total_seat }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Price Per Seat
                                </label>

                                <div>
                                    <strong>
                                        $ {{ number_format($trip->price_per_seat, 2) }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Ride Status
                                </label>

                                <div>

                                    @if ($trip->ride_status == 'active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @elseif($trip->ride_status == 'completed')
                                        <span class="badge bg-primary">
                                            Completed
                                        </span>
                                    @elseif($trip->ride_status == 'cancelled')
                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($trip->ride_status) }}
                                        </span>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- CAR DETAILS --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Car Details
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Car Name
                                </label>

                                <div>
                                    {{ $trip->car_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    Car Color
                                </label>

                                <div>
                                    {{ $trip->color ?? 'N/A' }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- ROUTE INFO --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Route Information
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    From Latitude
                                </label>

                                <div>
                                    {{ $trip->from_latitude }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold text-muted mb-1">
                                    From Longitude
                                </label>

                                <div>
                                    {{ $trip->from_longitude }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-semibold text-muted mb-1">
                                    To Latitude
                                </label>

                                <div>
                                    {{ $trip->to_latitude }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-semibold text-muted mb-1">
                                    To Longitude
                                </label>

                                <div>
                                    {{ $trip->to_longitude }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- PASSENGERS --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Trip Passengers
                        </h4>
                    </div>

                    <div class="card-body">

                        @forelse($trip->bookings as $booking)
                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex align-items-center">

                                    <div class="me-3">

                                        <img src="{{ $booking->user->avatar ?? asset('default.png') }}"
                                            class="rounded-circle border" width="60" height="60"
                                            style="object-fit: cover">

                                    </div>

                                    <div class="flex-grow-1">

                                        <h5 class="mb-1">
                                            {{ $booking->user->name ?? 'N/A' }}
                                        </h5>

                                        <div class="text-muted small">
                                            {{ $booking->user->email ?? 'N/A' }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $booking->user->phone ?? 'N/A' }}
                                            @if ($booking->user->phone_verified_at)
                                                <span class="badge bg-success ms-1">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="badge bg-danger ms-1">
                                                    Unverified
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">
                                            Seat Count
                                        </small>

                                        <strong>
                                            {{ $booking->seat_count }}
                                        </strong>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">
                                            Total Price
                                        </small>

                                        <strong class="fw-bold">
                                            $ {{ number_format($booking->total_price, 2) }}
                                        </strong>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">
                                            Status
                                        </small>

                                        @if ($booking->status == 'approved')
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        @elseif($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                @if ($booking->created_at)
                                    <div class="mt-2 text-muted small">
                                        Booked At:
                                        {{ $booking->created_at->format('d M Y h:i A') }}
                                    </div>
                                @endif

                            </div>

                        @empty

                            <div class="text-center text-muted">
                                No passengers found.
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- PUBLISHER --}}
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Publisher Information
                        </h4>
                    </div>

                    <div class="card-body text-center">

                        <img src="{{ $trip->publisher->avatar ?? asset('default.png') }}"
                            class="rounded-circle mb-3 border" width="100" height="100" style="object-fit: cover">

                        <h5 class="mb-1">
                            {{ $trip->publisher->name ?? 'N/A' }}
                        </h5>

                        <p class="text-muted mb-1">
                            {{ $trip->publisher->email ?? 'N/A' }}
                        </p>

                        <p class="text-muted mb-0">
                            {{ $trip->publisher->phone ?? 'N/A' }}
                        </p>

                    </div>
                </div>

                {{-- MATCH INFO --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Match Information
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="text-center mb-3">

                            <h1 class="fw-bold">
                                {{ $trip->match_percentage ?? 0 }}%
                            </h1>

                            <p class="text-muted mb-0">
                                Compatibility Score
                            </p>

                        </div>

                        <div>

                            @forelse($trip->matches ?? [] as $match)
                                <span class="badge bg-primary me-1 mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $match)) }}
                                </span>

                            @empty

                                <div class="text-muted">
                                    No match data found.
                                </div>
                            @endforelse

                        </div>

                    </div>
                </div>

                {{-- REVIEWS --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Reviews
                        </h4>
                    </div>

                    <div class="card-body">

                        @forelse($trip->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">

                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <h6 class="mb-0">
                                        {{ $review->user->name ?? 'User' }}
                                    </h6>

                                    <span class="text-dark">
                                        {{ $review->review }}
                                    </span>
                                    <span class="badge bg-warning text-dark">
                                        {{ $review->star }}/5
                                    </span>

                                </div>

                                <p class="text-muted mb-0">
                                    {{ $review->comment }}
                                </p>

                            </div>

                        @empty

                            <div class="text-muted text-center">
                                No reviews available.
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="card mt-3">
                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title">
                            Actions
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="d-grid gap-2">

                            <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
                                Back To List
                            </a>

                            @if ($trip->ride_status != 'completed')
                                <form action="{{ route('admin.trips.status', $trip->id) }}" method="POST">

                                    @csrf

                                    <input type="hidden" name="ride_status" value="completed">

                                    <button type="submit" class="btn btn-success w-100">
                                        Mark As Completed
                                    </button>

                                </form>
                            @endif


                            @if ($trip->ride_status != 'cancelled')
                                <form action="{{ route('admin.trips.status', $trip->id) }}" method="POST">

                                    @csrf

                                    <input type="hidden" name="ride_status" value="cancelled">

                                    <button type="submit" class="btn btn-danger w-100">
                                        Cancel Trip
                                    </button>

                                </form>
                            @endif


                            @if ($trip->ride_status != 'active')
                                <form action="{{ route('admin.trips.status', $trip->id) }}" method="POST">

                                    @csrf

                                    <input type="hidden" name="ride_status" value="active">

                                    <button type="submit" class="btn btn-primary w-100">
                                        Mark As Active
                                    </button>

                                </form>
                            @endif

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
