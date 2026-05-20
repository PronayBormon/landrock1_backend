@extends('backend.master')

@push('title')
    Edit Booking
@endpush

@section('content')
<div class="page-container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Edit Booking</h4>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-soft-primary btn-sm">Back to Bookings</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Passenger</label>
                                <input type="text" class="form-control" value="{{ $booking->user->name ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trip</label>
                                <input type="text" class="form-control" value="{{ $booking->trip ? ($booking->trip->from_location . ' → ' . $booking->trip->to_location) : 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Seats</label>
                                <input type="number" name="seat_count" min="1" class="form-control" value="{{ old('seat_count', $booking->seat_count) }}">
                                @error('seat_count')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Price</label>
                                <input type="text" class="form-control" value="${{ number_format($booking->total_price, 2) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    @foreach(['pending','approved','rejected','cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Booking</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
