@extends('backend.master')

@push('title')
    Reports
@endpush

@section('content')
<div class="page-container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Reports</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Users</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalUsers) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Trips</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalTrips) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Bookings</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalBookings) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Reviews</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalReviews) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Chats</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalChats) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Total Messages</h5>
                                <p class="mb-0 fs-24 fw-semibold">{{ number_format($totalMessages) }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card card-body border border-dashed">
                                <h5 class="fs-16">Approved Revenue</h5>
                                <p class="mb-0 fs-24 fw-semibold">${{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
