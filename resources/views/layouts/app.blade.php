<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GoGoCar Rentals') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @can('isAdmin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Users List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cars.index') }}">Cars Fleet</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reservations.index') }}">Cars Rentals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('returns.index') }}">Cars Returns</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reviews.index') }}">Cars Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payment.index') }}">Payments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.index') }}">Admin Panel</a>
                            </li>
                        @endcan
                        @can('isUser')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reservations.session') }}">My Rentals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payment.user') }}">My Payments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reviews.user') }}">My Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('returns.user_returns') }}">My Cars Returns</a>
                            </li>
                        @endcan
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(Auth::user()->image_path)
                                        <img src="{{ asset('storage/' . Auth::user()->image_path) }}" alt="Profile Photo" class="img-thumbnail rounded-circle me-2" style="max-width: 32px; max-height: 32px;">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Profile Photo" class="img-thumbnail rounded-circle me-2" style="max-width: 32px; max-height: 32px;">
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
