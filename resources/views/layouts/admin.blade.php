<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel POS') }}</title>

    <!-- Bootstrap 5 CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <!-- Font Awesome -->
    <link href="{{ asset('assets/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-right: 1px solid #ebe5e5;


            transition: all 0.3s;
        }

        #sidebar .nav-link {
            color: var(--text-muted);

            padding: 0.8rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.2rem;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {


            background: #e6f2f6;
            color: var(--primary);
        }

        #sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }

        #main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }

        /* Language Switcher Styles */
        .lang-dropdown .dropdown-toggle::after {
            display: none;

        }

        .lang-icon {
            font-size: 1.1rem;
            color: #6c757d;
            transition: color 0.2s;
        }

        .btn-link {
            text-decoration: none;

        }

        .lang-icon:hover {
            color: #0d6efd;
            text-decoration: none;

        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            #sidebar.active {
                margin-left: 0;
            }

            #main-content {
                margin-left: 0;
            }
        }

        /* RTL Support */
        [dir="rtl"] #sidebar {
            left: auto;
            right: 0;
        }

        [dir="rtl"] #main-content {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }

        @media (max-width: 768px) {
            [dir="rtl"] #sidebar {
                margin-right: calc(var(--sidebar-width) * -1);
                margin-left: 0;
            }

            [dir="rtl"] #sidebar.active {
                margin-right: 0;
            }

            [dir="rtl"] #main-content {
                margin-right: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="d-flex flex-column p-3">
        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-black text-decoration-none">
            <i class="fas fa-cash-register fa-2x me-2"></i>
            <span class="fs-4">{{ __('messages.pos_system') }}</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    {{ __('messages.dashboard') }}
                </a>
            </li>
            <li>
                <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('messages.pos_sale') }}
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}"
                    class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    {{ __('messages.orders') }}
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
                <hr class="text-white-50">
                <div class="small text-lourcase text--50 mb-2 px-2">{{ __('messages.management') }}</div>
                <li><a href="{{ route('products.index') }}"
                        class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"><i class="fas fa-box"></i>
                        {{ __('messages.products') }}</a></li>
                <li><a href="{{ route('categories.index') }}"
                        class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"><i
                            class="fas fa-tags"></i> {{ __('messages.categories') }}</a></li>
                <li><a href="{{ route('customers.index') }}"
                        class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"><i
                            class="fas fa-users"></i> {{ __('messages.customers') }}</a></li>
            @endif
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle"
                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                    style="width: 32px; height: 32px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <strong>{{ auth()->user()->name }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('messages.profile') }}</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">{{ __('messages.sign_out') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary d-md-none">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex w-100 justify-content-between align-items-center ms-3">
                    <h5 class="m-0 text-muted">@yield('title', 'Dashboard')</h5>

                    <div class="d-flex align-items-center">
                        <!-- Language Switcher Icon -->
                        <div class="dropdown lang-dropdown me-3">
                            <button class="btn btn-link p-0 dropdown-toggle" type="button" id="langDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-globe lang-icon"></i>
                                <span class="small text-uppercase fw-bold text-secondary">{{ app()->getLocale() }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                        href="{{ route('lang.switch', 'en') }}">
                                        <span>English</span>
                                        @if(app()->getLocale() == 'en') <i class="fas fa-check small"></i> @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center {{ app()->getLocale() == 'ar' ? 'active' : '' }}"
                                        href="{{ route('lang.switch', 'ar') }}">
                                        <span>العربية</span>
                                        @if(app()->getLocale() == 'ar') <i class="fas fa-check small"></i> @endif
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- User Role Badge -->
                        <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : 'success' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <!-- Alert Section -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>