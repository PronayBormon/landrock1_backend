{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
<?php

use App\Models\SystemSetting;

$system = SystemSetting::first();
?>

@extends('auth.master')
@section('content')
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xxl-3 col-lg-5 col-md-6">

            <!-- Logo -->
            <a href="{{ url('/') }}"
                class="auth-brand d-flex justify-content-center mb-3">
                <img src="{{ asset($system->dark_logo ?? 'backend/assets/images/logo-dark.png') }}"
                    style="min-height: 80px; width:auto"
                    height="26"
                    class="logo-dark">
                <img src="{{ asset($system->dark_logo ?? 'backend/assets/images/logo.png') }}"
                    style="min-height: 80px; width:auto"
                    height="26"
                    class="logo-light">

            </a>

            <div class="card overflow-hidden p-xxl-4 p-3 mb-0">
                <h4 class="fw-semibold mb-3 fs-18 text-center">Log in to your account</h4>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST"
                    action="{{ route('login') }}"
                    class="text-start">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your email"
                            required
                            autofocus>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox"
                                name="remember"
                                class="form-check-input"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label"
                                for="remember">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-muted border-bottom border-dashed">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit"
                            class="btn btn-primary fw-semibold">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Register -->
                @if (Route::has('register'))
                    <p class="text-muted fs-14 mt-3 mb-0 text-center">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="fw-semibold text-danger ms-1">
                            Sign Up
                        </a>
                    </p>
                @endif
            </div>

            <p class="mt-4 text-center mb-0">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Copyright
            </p>

        </div>
    </div>
@endsection
