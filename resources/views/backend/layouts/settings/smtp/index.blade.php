@extends('backend.master')

@push('title')
    {{ ucfirst($service) }} Credentials
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed">
                        <h4 class="header-title mb-0 text-capitalize">
                            {{ $service }} Credentials
                        </h4>
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

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST"
                            action="{{ route('admin.credentials.update', $service) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                @foreach ($credentials as $key => $value)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            {{ str_replace('_', ' ', $key) }}
                                        </label>

                                        <input
                                            type="{{ str_contains(strtolower($key), 'password') || str_contains(strtolower($key), 'secret') ? 'password' : 'text' }}"
                                            name="{{ $key }}"
                                            value="{{ old($key, $value) }}"
                                            class="form-control @error($key) is-invalid @enderror">

                                        @error($key)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach

                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-primary px-4">
                                    Update {{ ucfirst($service) }}
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
