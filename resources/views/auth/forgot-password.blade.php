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
                Forgot your password?
            </p>

            <div class="card overflow-hidden text-center p-xxl-4 p-3 mb-0">

                <h4 class="fw-semibold mb-3 fs-18">Reset Your Password</h4>

                <p class="text-muted mb-4">
                    Enter your email address and we’ll send you a password reset link.
                </p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Reset Form -->
                <form method="POST"
                    action="{{ route('password.email') }}"
                    class="text-start mb-3">
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit"
                            class="btn btn-primary fw-semibold">
                            Email Password Reset Link
                        </button>
                    </div>
                </form>

                <!-- Back to Login -->
                <p class="text-muted fs-14 mb-0">
                    Back to
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
