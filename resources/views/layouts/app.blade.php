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
    <body class="font-sans antialiased">
        <div class="min-vh-100 bg-light">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="container py-3">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-4">
                <div class="container">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
