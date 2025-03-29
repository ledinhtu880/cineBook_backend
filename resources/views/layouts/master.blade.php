<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'Hệ thống Quản lý Thông tin Địa lý')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('css/libs/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/ol.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <!-- Vendor Scripts -->
    <script type="text/javascript" src="{{ asset('javascript/libs/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/libs/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/libs/ol.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/form-validation.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('javascript/toast.js') }}" defer></script>

    @stack('css')
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row p-0 g-0">
            <!-- Header -->
            <div class="col-md-12">
                @include('layouts.header')
            </div>

            <!-- Sidebar -->
            <div class="col-md-2 p-0" id="sidebar-wrapper">
                <aside class="sidebar sidebar-initializing preload">
                    @include('layouts.sidebar')
                </aside>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-0" id="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).ready(function () {
            // Khởi tạo và hiển thị toast
            $('.toast').toast({
                autohide: true,
                delay: 2000,
            }).toast('show');
            window.csrfToken = "{{ csrf_token() }}";
            $('[data-bs-toggle="tooltip"]').tooltip(); // Kích hoạt tooltip cho menu khi ẩn
        });
    </script>
    @stack('javascript')
</body>

</html>