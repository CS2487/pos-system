<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Bootstrap 5 CSS -->
        @if(app()->getLocale() == 'ar')
            <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
        @else
            <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        @endif
        <!-- Font Awesome -->
        <link href="{{ asset('assets/fontawesome/css/all.min.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased text-dark bg-light">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5 bg-light">
            <div class="mb-4">
                <a href="/">
                    <x-application-logo class="w-20 h-20 text-secondary" />
                </a>
            </div>

            <div class="w-100 p-4 bg-white shadow-sm rounded-3" style="max-width: 400px;">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
