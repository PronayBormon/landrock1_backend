@extends('backend.master')

@push('title')
    Edit Ride Request
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Edit Ride Request</h4>
                            <p class="text-muted fs-13 mb-0">Update ride request details.</p>
                        </div>
                        <a href="{{ route('admin.ride-requests.index') }}" class="btn btn-sm btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Please fix the errors below:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.ride-requests.update', $rideRequest->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- User / Rider Selection --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">User (Rider) <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_select" class="form-select select2 @error('user_id') is-invalid @enderror" required>
                                        <option value="">Select a user...</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $rideRequest->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- FROM Location --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">FROM <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-map-pin text-danger"></i></span>
                                        <input type="text"
                                               name="from_location"
                                               class="form-control @error('from_location') is-invalid @enderror"
                                               value="{{ old('from_location', $rideRequest->from_location) }}"
                                               placeholder="e.g. Dhaka"
                                               required>
                                    </div>
                                    @error('from_location')
                                        <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TO Location --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">TO <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-map-pin text-danger"></i></span>
                                        <input type="text"
                                               name="to_location"
                                               class="form-control @error('to_location') is-invalid @enderror"
                                               value="{{ old('to_location', $rideRequest->to_location) }}"
                                               placeholder="e.g. Dhaka"
                                               required>
                                    </div>
                                    @error('to_location')
                                        <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TRAVEL DATE --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">TRAVEL DATE <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        <input type="date"
                                               name="travel_date"
                                               class="form-control @error('travel_date') is-invalid @enderror"
                                               value="{{ old('travel_date', $rideRequest->travel_date ? $rideRequest->travel_date->format('Y-m-d') : '') }}"
                                               required>
                                    </div>
                                    @error('travel_date')
                                        <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PREFERRED TIME (OPTIONAL) --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">PREFERRED TIME (OPTIONAL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                        <input type="text"
                                               name="preferred_time"
                                               id="preferred_time_input"
                                               list="time_suggestions"
                                               class="form-control @error('preferred_time') is-invalid @enderror"
                                               value="{{ old('preferred_time', $rideRequest->preferred_time) }}"
                                               placeholder="Anytime">
                                        <datalist id="time_suggestions">
                                            <option value="Anytime">
                                            <option value="Morning (6:00 AM - 12:00 PM)">
                                            <option value="Afternoon (12:00 PM - 5:00 PM)">
                                            <option value="Evening (5:00 PM - 9:00 PM)">
                                            <option value="Night (9:00 PM onwards)">
                                        </datalist>
                                    </div>
                                    @error('preferred_time')
                                        <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- NOTE (OPTIONAL) --}}
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label fw-semibold mb-1">NOTE (OPTIONAL)</label>
                                        <small class="text-muted" id="noteCharCounter">0/300</small>
                                    </div>
                                    <textarea name="note"
                                              id="noteTextarea"
                                              rows="4"
                                              maxlength="300"
                                              class="form-control @error('note') is-invalid @enderror"
                                              placeholder="Add any additional information that could help drivers find you, such as landmarks, flexible timing, luggage, etc.">{{ old('note', $rideRequest->note) }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- SEATS NEEDED --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">SEATS NEEDED</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-users"></i></span>
                                        <input type="number"
                                               name="seats_needed"
                                               min="1"
                                               max="10"
                                               class="form-control @error('seats_needed') is-invalid @enderror"
                                               value="{{ old('seats_needed', $rideRequest->seats_needed ?? 1) }}">
                                    </div>
                                    @error('seats_needed')
                                        <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- STATUS --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">STATUS</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', $rideRequest->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status', $rideRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ old('status', $rideRequest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $rideRequest->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                                <a href="{{ route('admin.ride-requests.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i> Update Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            const noteTextarea = document.getElementById('noteTextarea');
            const noteCounter = document.getElementById('noteCharCounter');

            function updateCounter() {
                if (noteTextarea && noteCounter) {
                    const currentLen = noteTextarea.value.length;
                    noteCounter.textContent = `${currentLen}/300`;
                }
            }

            if (noteTextarea) {
                noteTextarea.addEventListener('input', updateCounter);
                updateCounter();
            }

            $('#user_select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Search user by name or email...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
