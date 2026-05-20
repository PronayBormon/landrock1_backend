@extends('backend.master')

@push('title')
    Edit User
@endpush

@section('content')

    <div class="page-container">

        <div class="row">

            {{-- PROFILE SIDEBAR --}}
            <div class="col-md-4">

                <div class="card">

                    <div class="card-body text-center">

                        {{-- AVATAR --}}
                        <div class="mb-3">

                            <img src="{{ $user->avatar ?: asset('default.png') }}" alt="User" class="rounded-circle border"
                                width="140" height="140" style="object-fit: cover;">

                        </div>

                        {{-- NAME --}}
                        <h4 class="mb-1">
                            {{ $user->name }}
                        </h4>

                        {{-- ROLE --}}
                        <span class="badge bg-primary mb-3">
                            {{ ucfirst($user->role) }}
                        </span>

                        {{-- EMAIL --}}
                        <div class="mb-2">

                            <small class="text-muted d-block">
                                Email
                            </small>

                            <div>
                                {{ $user->email }}
                            </div>

                        </div>

                        {{-- PHONE --}}
                        <div class="mb-2">

                            <small class="text-muted d-block">
                                Phone
                            </small>

                            <div>

                                {{ $user->phone ?? 'N/A' }}

                                @if ($user->phone_verified_at)
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

                        {{-- AVG REVIEW --}}
                        <div class="mb-2">

                            <small class="text-muted d-block">
                                Average Review
                            </small>

                            <div>
                                ⭐ {{ $user->avg_review }}/5
                            </div>

                        </div>

                        {{-- BIO --}}
                        <div class="mt-4 text-start">

                            <small class="text-muted d-block mb-1">
                                Bio
                            </small>

                            <p class="mb-0">
                                {{ $user->bio ?: 'No bio available.' }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-md-8">

                <div class="card">

                    <div class="card-header border-bottom border-dashed d-flex align-items-center">

                        <h4 class="header-title">
                            Edit User
                        </h4>

                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary ms-auto">
                            Back
                        </a>

                    </div>

                    <div class="card-body">

                        {{-- GLOBAL ERRORS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <div class="row g-3">

                                {{-- NAME --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Name
                                    </label>

                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- EMAIL --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- PHONE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Phone
                                    </label>

                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}">

                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- ROLE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Role
                                    </label>

                                    <select name="role" class="form-select @error('role') is-invalid @enderror">

                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>

                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                            User
                                        </option>

                                    </select>

                                    @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- PASSWORD --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Password
                                    </label>

                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- CONFIRM PASSWORD --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Confirm Password
                                    </label>

                                    <input type="password" name="password_confirmation" class="form-control">

                                </div>

                                {{-- BIO --}}
                                <div class="col-12">

                                    <label class="form-label">
                                        Bio
                                    </label>

                                    <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $user->bio) }}</textarea>

                                    @error('bio')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- RIDE STYLE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Ride Style
                                    </label>

                                    <select name="ride_style" class="form-select @error('ride_style') is-invalid @enderror">

                                        <option value="">
                                            Select Ride Style
                                        </option>

                                        <option value="détendu"
                                            {{ old('ride_style', $user->ride_style) == 'détendu' ? 'selected' : '' }}>
                                            Détendu
                                        </option>

                                        <option value="bavard"
                                            {{ old('ride_style', $user->ride_style) == 'bavard' ? 'selected' : '' }}>
                                            Bavard
                                        </option>

                                        <option value="calme"
                                            {{ old('ride_style', $user->ride_style) == 'calme' ? 'selected' : '' }}>
                                            Calme
                                        </option>

                                        <option value="idéal_pour_travailler"
                                            {{ old('ride_style', $user->ride_style) == 'idéal_pour_travailler' ? 'selected' : '' }}>
                                            Idéal Pour Travailler
                                        </option>

                                    </select>

                                    @error('ride_style')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- MUSIC PREFERENCE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Music Preference
                                    </label>

                                    <select name="music_preference"
                                        class="form-select @error('music_preference') is-invalid @enderror">

                                        <option value="">
                                            Select Music Preference
                                        </option>

                                        <option value="rap"
                                            {{ old('music_preference', $user->music_preference) == 'rap' ? 'selected' : '' }}>
                                            Rap
                                        </option>

                                        <option value="pop"
                                            {{ old('music_preference', $user->music_preference) == 'pop' ? 'selected' : '' }}>
                                            Pop
                                        </option>

                                        <option value="afro"
                                            {{ old('music_preference', $user->music_preference) == 'afro' ? 'selected' : '' }}>
                                            Afro
                                        </option>

                                        <option value="rock"
                                            {{ old('music_preference', $user->music_preference) == 'rock' ? 'selected' : '' }}>
                                            Rock
                                        </option>

                                        <option value="sans_musique"
                                            {{ old('music_preference', $user->music_preference) == 'sans_musique' ? 'selected' : '' }}>
                                            Sans Musique
                                        </option>

                                    </select>

                                    @error('music_preference')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- CONVERSATION LEVEL --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Conversation Level
                                    </label>

                                    <select name="conversation_level"
                                        class="form-select @error('conversation_level') is-invalid @enderror">

                                        <option value="">
                                            Select Conversation Level
                                        </option>

                                        <option value="j’aime_discuter"
                                            {{ old('conversation_level', $user->conversation_level) == 'j’aime_discuter' ? 'selected' : '' }}>
                                            J’aime Discuter
                                        </option>

                                        <option value="un_peu"
                                            {{ old('conversation_level', $user->conversation_level) == 'un_peu' ? 'selected' : '' }}>
                                            Un Peu
                                        </option>

                                        <option value="je_préfère_le_calme"
                                            {{ old('conversation_level', $user->conversation_level) == 'je_préfère_le_calme' ? 'selected' : '' }}>
                                            Je Préfère Le Calme
                                        </option>

                                    </select>

                                    @error('conversation_level')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- SMOKE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Smoke
                                    </label>

                                    <select name="smoke" class="form-select @error('smoke') is-invalid @enderror">

                                        <option value="">
                                            Select
                                        </option>

                                        <option value="oui"
                                            {{ old('smoke', $user->smoke) == 'oui' ? 'selected' : '' }}>
                                            Oui
                                        </option>

                                        <option value="non"
                                            {{ old('smoke', $user->smoke) == 'non' ? 'selected' : '' }}>
                                            Non
                                        </option>

                                    </select>

                                    @error('smoke')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- PET --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Pet
                                    </label>

                                    <input type="text" name="pet"
                                        class="form-control @error('pet') is-invalid @enderror"
                                        value="{{ old('pet', $user->pet) }}">

                                    @error('pet')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- CONNECT LIKE RIDER --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Connect Like Rider
                                    </label>

                                    <input type="text" name="connect_like_rider"
                                        class="form-control @error('connect_like_rider') is-invalid @enderror"
                                        value="{{ old('connect_like_rider', $user->connect_like_rider) }}">

                                    @error('connect_like_rider')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- WHAT KIND RIDE --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        What Kind Ride
                                    </label>

                                    <input type="text" name="what_kind_ride"
                                        class="form-control @error('what_kind_ride') is-invalid @enderror"
                                        value="{{ old('what_kind_ride', $user->what_kind_ride) }}">

                                    @error('what_kind_ride')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- INTERESTED --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Interested
                                    </label>

                                    <textarea name="interested" rows="3" class="form-control @error('interested') is-invalid @enderror">{{ old('interested', is_array($user->interested) ? implode(', ', $user->interested) : '') }}</textarea>

                                    <small class="text-muted">
                                        Separate by comma
                                    </small>

                                    @error('interested')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- PERSONALIZATION --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Personalization
                                    </label>

                                    <textarea name="personalization" rows="3" class="form-control @error('personalization') is-invalid @enderror">{{ old('personalization', is_array($user->personalization) ? implode(', ', $user->personalization) : '') }}</textarea>

                                    <small class="text-muted">
                                        Separate by comma
                                    </small>

                                    @error('personalization')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- AVATAR --}}
                                <div class="col-md-6">

                                    <x-chunk-upload name="avatar" label="Avatar" :value="$user->avatar" />

                                    @error('avatar')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- SUBMIT --}}
                                <div class="col-12 text-end mt-3">

                                    <button type="submit" class="btn btn-primary">
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
