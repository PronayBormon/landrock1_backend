@extends('backend.master')

@push('title')
    Dashboard
@endpush

@section('content')

<div class="page-container">

    {{-- TOP ANALYTICS --}}
    <div class="row">

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Users
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($totalUsers) }}
                            </h3>

                            <small class="text-success">
                                +{{ $newUsersThisMonth }} this month
                            </small>

                        </div>

                        <div>
                            <i class="ti ti-users fs-1 text-primary"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Trips
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($totalTrips) }}
                            </h3>

                            <small class="text-info">
                                {{ $activeTrips }} active trips
                            </small>

                        </div>

                        <div>
                            <i class="ti ti-route fs-1 text-success"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Bookings
                            </p>

                            <h3 class="mb-0">
                                {{ number_format($totalBookings) }}
                            </h3>

                            <small class="text-warning">
                                {{ $pendingBookings }} pending requests
                            </small>

                        </div>

                        <div>
                            <i class="ti ti-ticket fs-1 text-warning"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Revenue
                            </p>

                            <h3 class="mb-0">
                                ${{ number_format($totalRevenue, 2) }}
                            </h3>

                            <small class="text-success">
                                Monthly: ${{ number_format($monthlyRevenue, 2) }}
                            </small>

                        </div>

                        <div>
                            <i class="ti ti-cash fs-1 text-success"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- EXPORT BUTTONS --}}
    {{-- <div class="row mt-2">

        <div class="col-12">

            <div class="card">

                <div class="card-body d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.export.users') }}"
                       class="btn btn-primary">
                        Export Users
                    </a>

                    <a href="{{ route('admin.export.trips') }}"
                       class="btn btn-success">
                        Export Trips
                    </a>

                    <a href="{{ route('admin.export.bookings') }}"
                       class="btn btn-warning">
                        Export Bookings
                    </a>

                    <a href="{{ route('admin.export.revenue') }}"
                       class="btn btn-info">
                        Export Revenue
                    </a>

                    <a href="{{ route('admin.export.chats') }}"
                       class="btn btn-dark">
                        Export Chats
                    </a>

                </div>

            </div>

        </div>

    </div> --}}

    {{-- STATISTICS --}}
    <div class="row mt-2">

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header border-bottom border-dashed">

                    <h4 class="header-title">
                        Trip Statistics
                    </h4>

                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Active Trips</span>
                            <strong>{{ $activeTrips }}</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-success"
                                 style="width: {{ $totalTrips ? ($activeTrips / $totalTrips) * 100 : 0 }}%"></div>
                        </div>

                    </div>

                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Completed Trips</span>
                            <strong>{{ $completedTrips }}</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-primary"
                                 style="width: {{ $totalTrips ? ($completedTrips / $totalTrips) * 100 : 0 }}%"></div>
                        </div>

                    </div>

                    <div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Cancelled Trips</span>
                            <strong>{{ $cancelledTrips }}</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar bg-danger"
                                 style="width: {{ $totalTrips ? ($cancelledTrips / $totalTrips) * 100 : 0 }}%"></div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header border-bottom border-dashed">

                    <h4 class="header-title">
                        Chat Analytics
                    </h4>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <h2>
                            {{ number_format($totalChats) }}
                        </h2>

                        <p class="text-muted mb-0">
                            Total Conversations
                        </p>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Total Messages</span>

                        <strong>
                            {{ number_format($totalMessages) }}
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>Today Messages</span>

                        <strong>
                            {{ number_format($todayMessages) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header border-bottom border-dashed">

                    <h4 class="header-title">
                        Reviews & Ratings
                    </h4>

                </div>

                <div class="card-body text-center">

                    <h1 class="display-5">
                        ⭐ {{ number_format($averageRating, 1) }}
                    </h1>

                    <p class="text-muted">
                        Average Platform Rating
                    </p>

                    <hr>

                    <div>

                        <strong>
                            {{ number_format($totalReviews) }}
                        </strong>

                        <p class="text-muted mb-0">
                            Total Reviews
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- RECENT USERS --}}
    <div class="row mt-2">

        @foreach($recentUsers as $user)

            <div class="col-xl-4 col-md-6">

                <div class="card">

                    <div class="card-body d-flex align-items-center gap-3">

                        <img src="{{ $user->avatar ?: asset('/backend/assets/images/user.webp') }}"
                             class="rounded-circle"
                             width="70"
                             height="70"
                             style="object-fit: cover;">

                        <div>

                            <h5 class="mb-1">
                                {{ $user->name }}
                            </h5>

                            <p class="text-muted mb-1">
                                {{ $user->email }}
                            </p>

                            <div>
                                ⭐ {{ $user->avg_review }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    {{-- RECENT TRIPS --}}
    <div class="row mt-2">

        <div class="col-12">

            <div class="card">

                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">

                    <h4 class="header-title">
                        Latest Trips
                    </h4>

                    <a href="{{ route('admin.trips.index') }}"
                       class="btn btn-sm btn-primary">
                        View All
                    </a>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>Rider</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date</th>
                                    <th>Seats</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($recentTrips as $trip)

                                    <tr>

                                        <td>
                                            {{ $trip->publisher->name ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $trip->from_location }}
                                        </td>

                                        <td>
                                            {{ $trip->to_location }}
                                        </td>

                                        <td>
                                            {{ $trip->ride_date }}
                                        </td>

                                        <td>
                                            {{ $trip->available_seat }}
                                        </td>

                                        <td>
                                            ${{ $trip->price_per_seat }}
                                        </td>

                                        <td>

                                            @if($trip->ride_status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($trip->ride_status == 'completed')
                                                <span class="badge bg-primary">Completed</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif

                                        </td>

                                        <td>

                                            <a href="{{ route('admin.trips.show', $trip->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Details
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- RECENT BOOKINGS --}}
    <div class="row mt-2">

        <div class="col-12">

            <div class="card">

                <div class="card-header border-bottom border-dashed">

                    <h4 class="header-title">
                        Latest Bookings
                    </h4>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>User</th>
                                    <th>Trip</th>
                                    <th>Seats</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($recentBookings as $booking)

                                    <tr>

                                        <td>
                                            {{ $booking->user->name ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $booking->trip->from_location ?? '' }} → {{ $booking->trip->to_location ?? '' }}
                                        </td>

                                        <td>
                                            {{ $booking->seat_count }}
                                        </td>

                                        <td>
                                            ${{ $booking->total_price }}
                                        </td>

                                        <td>

                                            @if($booking->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif

                                        </td>

                                        <td>
                                            {{ $booking->created_at->format('d M Y') }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection