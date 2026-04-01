{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('auth.master')

@section('content')
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xxl-3 col-lg-5 col-md-6">

            <!-- Logo -->
            <a href="{{ url('/') }}"
                class="auth-brand d-flex justify-content-center mb-2">
                <img src="{{ asset('backend/assets/images/logo-dark.png') }}"
                    height="26"
                    class="logo-dark">
                <img src="{{ asset('backend/assets/images/logo.png') }}"
                    height="26"
                    class="logo-light">
            </a>

            <p class="fw-semibold mb-4 text-center text-muted fs-15">
                Create a new account
            </p>

            <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">
                <h4 class="fw-semibold mb-3 fs-18">Sign Up</h4>

                <!-- Register Form -->
                <form method="POST"
                    action="{{ route('register') }}"
                    class="text-start mb-3">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter your name"
                            required
                            autofocus>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your email"
                            required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Confirm your password"
                            required>
                    </div>

                    <!-- Terms -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox"
                                class="form-check-input"
                                id="terms"
                                required>
                            <label class="form-check-label"
                                for="terms">
                                I agree to all
                                <a href="#"
                                    class="link-dark text-decoration-underline">
                                    Terms & Conditions
                                </a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button class="btn btn-primary fw-semibold"
                            type="submit">
                            Sign Up
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <p class="text-muted fs-14 mb-0">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="fw-semibold text-danger ms-1">
                        Login
                    </a>
                </p>
            </div>

            <p class="mt-4 text-center mb-0">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Adminto
            </p>
        </div>
    </div>
@endsection
