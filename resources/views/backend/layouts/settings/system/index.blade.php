@extends('backend.master')

@push('title')
    System Settings
@endpush

@push('styles')
    <!-- Dropzone css -->
    <link rel="stylesheet"
        href="/backend/assets/vendor/dropzone/dropzone.css"
        type="text/css" />
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title mb-0">System Settings</h4>
                    </div>

                    <div class="card-body">

                        {{-- GLOBAL ERROR MESSAGE --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.dashboard.system.settings.update') }}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                {{-- LEFT COLUMN --}}
                                <div class="col-lg-6">

                                    <h5 class="mb-3">Basic Information</h5>

                                    {{-- Site Name --}}
                                    <div class="mb-3">
                                        <label class="form-label">Site Name</label>
                                        <input type="text"
                                            name="site_name"
                                            class="form-control @error('site_name') is-invalid @enderror"
                                            value="{{ old('site_name', $data->site_name) }}">
                                        @error('site_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Site Tagline --}}
                                    <div class="mb-3">
                                        <label class="form-label">Site Tagline</label>
                                        <input type="text"
                                            name="site_tagline"
                                            class="form-control @error('site_tagline') is-invalid @enderror"
                                            value="{{ old('site_tagline', $data->site_tagline) }}">
                                        @error('site_tagline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Contact Email --}}
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email"
                                            name="contact_email"
                                            class="form-control @error('contact_email') is-invalid @enderror"
                                            value="{{ old('contact_email', $data->contact_email) }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Support Email --}}
                                    <div class="mb-3">
                                        <label class="form-label">Support Email</label>
                                        <input type="email"
                                            name="support_email"
                                            class="form-control @error('support_email') is-invalid @enderror"
                                            value="{{ old('support_email', $data->support_email) }}">
                                        @error('support_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Phone --}}
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text"
                                            name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $data->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Address --}}
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address"
                                            rows="3"
                                            class="form-control @error('address') is-invalid @enderror">{{ old('address', $data->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                {{-- RIGHT COLUMN --}}
                                <div class="col-lg-6">

                                    <h5 class="mb-3">Branding</h5>

                                    {{-- Light Logo --}}
                                    <div class="mb-3">
                                        <x-chunk-upload name="logo"
                                            label="Favicon"
                                            :value="$data->logo" />
                                        @error('logo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Dark Logo --}}
                                    <div class="mb-3">
                                        <x-chunk-upload name="dark_logo"
                                            label="Logo"
                                            :value="$data->dark_logo" />
                                        @error('dark_logo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-primary px-4">
                                    Save Settings
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
@endpush
