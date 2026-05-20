@extends('backend.master')

@push('title')
    Edit Trip
@endpush

@section('content')

<div class="page-container">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            {{-- ERROR SUMMARY --}}
            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('admin.trips.update', $trip->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">

                        <h4 class="header-title mb-0">
                            Edit Trip
                        </h4>

                        <a href="{{ route('admin.trips.index') }}"
                           class="btn btn-secondary btn-sm">
                            Back
                        </a>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- FROM LOCATION --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    From Location
                                </label>

                                <input type="text"
                                       name="from_location"
                                       class="form-control @error('from_location') is-invalid @enderror"
                                       value="{{ old('from_location', $trip->from_location) }}">

                                @error('from_location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- TO LOCATION --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    To Location
                                </label>

                                <input type="text"
                                       name="to_location"
                                       class="form-control @error('to_location') is-invalid @enderror"
                                       value="{{ old('to_location', $trip->to_location) }}">

                                @error('to_location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- FROM LATITUDE --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    From Latitude
                                </label>

                                <input type="text"
                                       name="from_latitude"
                                       class="form-control @error('from_latitude') is-invalid @enderror"
                                       value="{{ old('from_latitude', $trip->from_latitude) }}">

                                @error('from_latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- FROM LONGITUDE --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    From Longitude
                                </label>

                                <input type="text"
                                       name="from_longitude"
                                       class="form-control @error('from_longitude') is-invalid @enderror"
                                       value="{{ old('from_longitude', $trip->from_longitude) }}">

                                @error('from_longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- TO LATITUDE --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    To Latitude
                                </label>

                                <input type="text"
                                       name="to_latitude"
                                       class="form-control @error('to_latitude') is-invalid @enderror"
                                       value="{{ old('to_latitude', $trip->to_latitude) }}">

                                @error('to_latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- TO LONGITUDE --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    To Longitude
                                </label>

                                <input type="text"
                                       name="to_longitude"
                                       class="form-control @error('to_longitude') is-invalid @enderror"
                                       value="{{ old('to_longitude', $trip->to_longitude) }}">

                                @error('to_longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- RIDE DATE --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Ride Date
                                </label>

                                <input type="date"
                                       name="ride_date"
                                       class="form-control @error('ride_date') is-invalid @enderror"
                                       value="{{ old('ride_date', $trip->ride_date) }}">

                                @error('ride_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- RIDE TIME --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Ride Time
                                </label>

                                <input type="time"
                                       name="ride_time"
                                       class="form-control @error('ride_time') is-invalid @enderror"
                                       value="{{ old('ride_time', $trip->ride_time) }}">

                                @error('ride_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- AVAILABLE SEAT --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Available Seat
                                </label>

                                <input type="number"
                                       name="available_seat"
                                       class="form-control @error('available_seat') is-invalid @enderror"
                                       value="{{ old('available_seat', $trip->available_seat) }}">

                                @error('available_seat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- TOTAL SEAT --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Total Seat
                                </label>

                                <input type="number"
                                       name="total_seat"
                                       class="form-control @error('total_seat') is-invalid @enderror"
                                       value="{{ old('total_seat', $trip->total_seat) }}">

                                @error('total_seat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- PRICE PER SEAT --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Price Per Seat ($)
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="price_per_seat"
                                       class="form-control @error('price_per_seat') is-invalid @enderror"
                                       value="{{ old('price_per_seat', $trip->price_per_seat) }}">

                                @error('price_per_seat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- CAR NAME --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Car Name
                                </label>

                                <input type="text"
                                       name="car_name"
                                       class="form-control @error('car_name') is-invalid @enderror"
                                       value="{{ old('car_name', $trip->car_name) }}">

                                @error('car_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- CAR COLOR --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Car Color
                                </label>

                                <input type="text"
                                       name="color"
                                       class="form-control @error('color') is-invalid @enderror"
                                       value="{{ old('color', $trip->color) }}">

                                @error('color')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- STATUS --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Ride Status
                                </label>

                                <select name="ride_status"
                                        class="form-select @error('ride_status') is-invalid @enderror">

                                    <option value="active"
                                        {{ old('ride_status', $trip->ride_status) == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="completed"
                                        {{ old('ride_status', $trip->ride_status) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled"
                                        {{ old('ride_status', $trip->ride_status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                                @error('ride_status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="card-footer border-top border-dashed">

                        <div class="d-flex justify-content-end">

                            <button type="submit"
                                    class="btn btn-primary">
                                Update Trip
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection