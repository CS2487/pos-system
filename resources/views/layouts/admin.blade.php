<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel POS') }}</title>

    <!-- Bootstrap -->
    @if(app()->getLocale() == 'ar')
        <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent Flash -->
    <script>
        (function () {
            if (localStorage.getItem('sidebar_state') === 'collapsed') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>


    @stack('styles')
</head>

<body>

<!-- Overlay for Mobile Sidebar -->
<div id="sidebar-overlay"></div>

<!-- Sidebar Navigation -->
<nav id="sidebar">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <i class="fas fa-cash-register"></i>
        <span>{{ __('messages.pos_system') }}</span>
    </a>
    
    <hr class="mx-3">

    <!-- Nav Links -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>{{ __('messages.dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ __('messages.pos_sale') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>{{ __('messages.orders') }}</span>
            </a>
        </li>

        @if(auth()->user()->role === 'admin')
            <hr class="mx-3 text-white-50">
            <div class="small text-uppercase text-muted mb-2 px-3 management-label">
                <span class="fw-bold">{{ __('messages.management') }}</span>
            </div>
            
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>{{ __('messages.products') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('messages.categories') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>{{ __('messages.customers') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i>
                    <span>{{ __('messages.suppliers') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>{{ __('messages.purchases') }}</span>
                </a>
            </li>
        @endif
    </ul>

    <hr class="mx-3 mt-auto">
    
    <!-- User dropdown (Bottom of sidebar) -->
<div class="dropdown px-3 mb-3">
    <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <!-- <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
             style="width:32px; height:32px; background-color: #314158; color: white; font-weight: bold; font-size: 14px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div> -->


        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
     style="width: 2.5vw; height: 2.5vw; min-width: 30px; min-height: 30px; background-color: #314158; color: white; font-weight: bold; font-size: 1.2vw; min-font-size: 12px;">
    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
</div>

        <strong class="text-truncate">{{ auth()->user()->name }}</strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark shadow text-small" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('messages.profile') }}</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">{{ __('messages.sign_out') }}</button>
            </form>
        </li>
    </ul>
</div>
</nav>

<!-- Main Content Wrapper -->
<div id="main-content">
    
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom sticky-top">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button -->
            <button type="button" id="sidebarCollapse" class="btn btn-light border">
                <i class="fas fa-bars text-primary"></i>
            </button>

            <!-- Title & Controls -->
            <div class="d-flex w-100 justify-content-between align-items-center ms-3">
                <h5 class="m-0 text-muted d-none d-md-block">@yield('title', 'Dashboard')</h5>

                <div class="d-flex align-items-center gap-3">
                    <!-- Language Switcher -->
                    <div class="dropdown lang-dropdown">
                        <button class="btn btn-link p-0 dropdown-toggle text-decoration-none" type="button" id="langDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-globe lang-icon"></i>
                            <span class="small text-uppercase fw-bold text-secondary ms-1">{{ app()->getLocale() }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="langDropdown">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center {{ app()->getLocale()=='en'?'active':'' }}" href="{{ route('lang.switch','en') }}">
                                    <span>English</span>@if(app()->getLocale()=='en')<i class="fas fa-check small"></i>@endif
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center {{ app()->getLocale()=='ar'?'active':'' }}" href="{{ route('lang.switch','ar') }}">
                                    <span>العربية</span>@if(app()->getLocale()=='ar')<i class="fas fa-check small"></i>@endif
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Role Badge -->
                    <span class="badge bg-{{ auth()->user()->role==='admin'?'danger':'success' }} rounded-pill px-3">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dynamic Content Container -->
    <div class="container-fluid p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const body = document.body;
        const isRTL = document.documentElement.dir === 'rtl';

        // Function to close sidebar (Mobile)
        function closeSidebarMobile() {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('active');
                overlay.classList.remove('show');
            }
        }

        // Toggle Button Logic
        sidebarCollapse?.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                // Mobile Behavior: Toggle Active Class + Overlay
                sidebar.classList.toggle('active');
                overlay.classList.toggle('show');
            } else {
                // Desktop Behavior: Toggle Collapsed Class + LocalStorage
                const collapsed = body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar_state', collapsed ? 'collapsed' : 'expanded');
            }
        });

        // Close sidebar when clicking the overlay
        overlay?.addEventListener('click', closeSidebarMobile);

        // Close sidebar when clicking a nav link on mobile (optional, for better UX)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', closeSidebarMobile);
        });

        // Handle Window Resize
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                // Clean up mobile states when resizing to desktop
                overlay.classList.remove('show');
                sidebar.classList.remove('active'); 
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>