       <?php

        use App\Models\SystemSetting;

        $system = SystemSetting::first();
        ?>

       <!DOCTYPE html>
       <html lang="en"
           data-layout="">

       <head>
           <meta charset="utf-8" />
           <title>Log In | Welcome to website</title>

           <meta name="viewport"
               content="width=device-width, initial-scale=1.0">
           <meta name="description"
               content="Login page">
           <meta name="author"
               content="Coderthemes">

           <!-- App favicon -->
           <link rel="shortcut icon"
               href="{{ asset($system->logo ?? '') }}">
           @include('backend.partials.styles')

           <!-- Theme Config -->
           <script src="{{ asset('backend/assets/js/config.js') }}"></script>

           <!-- Vendor CSS -->
           <link href="{{ asset('backend/assets/css/vendor.min.css') }}"
               rel="stylesheet" />

           <!-- App CSS -->
           <link href="{{ asset('backend/assets/css/app.min.css') }}"
               rel="stylesheet"
               id="app-style" />

           <!-- Icons -->
           <link href="{{ asset('backend/assets/css/icons.min.css') }}"
               rel="stylesheet" />
       </head>

       <body>

           <div class="auth-bg d-flex min-vh-100">
               @yield('content')
           </div>

           <!-- Vendor JS -->
           <script src="{{ asset('backend/assets/js/vendor.min.js') }}"></script>

           <!-- App JS -->
           <script src="{{ asset('backend/assets/js/app.js') }}"></script>

           <!-- Toast Container -->
           <div id="toastContainer"
               class="position-fixed top-0 end-0 p-3"
               style="z-index: 1055;"></div>

           @if (session('t-error'))
           <script>
               document.addEventListener('DOMContentLoaded', function() {
                   const container = document.getElementById('toastContainer');
                   const toastId = 'toast-error-' + Date.now();
                   const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('t-error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
                   container.insertAdjacentHTML('beforeend', toastHtml);
                   const toastElement = document.getElementById(toastId);
                   const bsToast = new bootstrap.Toast(toastElement, {
                       delay: 5000
                   });
                   bsToast.show();
               });
           </script>
           @endif
           @foreach (['t-success', 't-error', 't-info', 't-warning'] as $msg)
           @if (session($msg))
           <script>
               showToast('{{ $msg }}', '{{ session($msg) }}');
           </script>
           @endif
           @endforeach


       </body>

       </html>  