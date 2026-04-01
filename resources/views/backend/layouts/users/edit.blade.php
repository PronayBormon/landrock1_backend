@extends('backend.master')

@push('title')
    Edit User
@endpush

@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Edit User</h4>
                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-sm btn-secondary ms-auto">
                            Back
                        </a>
                    </div>

                    <div class="card-body">

                        {{-- GLOBAL ERRORS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the errors below.</strong>
                            </div>
                        @endif

                        <form method="POST"
                            action="{{ route('admin.users.update', $user->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">

                                {{-- Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password (optional) --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Password
                                        <small class="text-muted">(Leave blank to keep current)</small>
                                    </label>
                                    <input type="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password"
                                        name="password_confirmation"
                                        class="form-control">
                                </div>

                                {{-- Role --}}
                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <select name="role"
                                        class="form-select @error('role') is-invalid @enderror">
                                        <option value="admin"
                                            {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="user"
                                            {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                            User
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Avatar --}}
                                <div class="col-md-6">
                                    <label class="form-label">Avatar</label>
                                    <x-chunk-upload name="avatar"
                                        label="Avatar"
                                        :value="$user->avatar" />
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="text-muted d-block mt-1">
                                        JPG, PNG, WEBP • Max 2MB
                                    </small>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary">
                                        Update User
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
