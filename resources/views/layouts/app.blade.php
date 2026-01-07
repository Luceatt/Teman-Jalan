<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Teman Jalan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center group">
                            <div class="bg-blue-600 text-white p-2 rounded-lg mr-3 group-hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-map-marked-alt text-xl"></i>
                            </div>
                            <span class="font-bold text-xl text-gray-900 tracking-tight">Teman Jalan</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('places.index') }}"
                               class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('places.*') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                                {{ __('Places') }}
                            </a>
                            <a href="{{ route('rundowns.index') }}"
                               class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('rundowns.*') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                                {{ __('Rundown') }}
                            </a>
                            <a href="{{ url('/friends') }}"
                               class="text-sm font-medium transition-colors duration-150 {{ request()->is('friends*') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                                {{ __('Friends') }}
                            </a>
                            <a href="{{ route('history.index') }}"
                               class="text-sm font-medium transition-colors duration-150 {{ request()->routeIs('history.*') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                                {{ __('History') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Language Switcher & User Menu -->
                    <div class="hidden md:flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <div class="relative" id="lang-menu-container">
                            <button type="button" id="lang-menu-button" class="flex items-center text-gray-500 hover:text-gray-900 focus:outline-none">
                                <span class="mr-1">{{ App::getLocale() == 'id' ? '🇮🇩' : '🇬🇧' }}</span>
                                <span class="text-sm font-medium uppercase">{{ App::getLocale() }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                             <div id="lang-menu-dropdown"
                                     class="hidden absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     role="menu">
                                    <a href="{{ route('lang.switch', 'id') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        🇮🇩 Indonesian
                                    </a>
                                    <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        🇬🇧 English
                                    </a>
                                </div>
                        </div>

                        @auth
                            <div class="relative ml-3" id="user-menu-container">
                                <div>
                                    <button type="button" id="user-menu-button"
                                            class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold uppercase">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="ml-3 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-400"></i>
                                    </button>
                                </div>
                                <div id="user-menu-dropdown"
                                     class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <i class="fas fa-user mr-2 w-4"></i> {{ __('Profile') }}
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50" role="menuitem">
                                            <i class="fas fa-sign-out-alt mr-2 w-4"></i> {{ __('Sign out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 font-medium text-sm">{{ __('Log in') }}</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">{{ __('Sign up') }}</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button type="button" id="mobile-menu-button"
                                class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Open main menu</span>
                            <i class="fas fa-bars text-xl" id="menu-icon-open"></i>
                            <i class="fas fa-times text-xl hidden" id="menu-icon-close"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div class="hidden md:hidden border-t border-gray-200" id="mobile-menu">
                <div class="space-y-1 pt-2 pb-3 px-2">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('places.index') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('places.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            Tempat
                        </a>
                        <a href="{{ route('rundowns.index') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('rundowns.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            Rundown
                        </a>
                        <a href="{{ url('/friends') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->is('friends*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            Teman
                        </a>
                         <a href="{{ route('history.index') }}"
                           class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs('history.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            Riwayat
                        </a>
                    @endauth
                </div>
                
                @auth
                    <div class="border-t border-gray-200 pt-4 pb-4">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold uppercase text-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <a href="{{ route('profile') }}"
                               class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">
                                Your Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-red-600 hover:bg-gray-50 hover:text-red-700">
                                    {{ __('Sign out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-4 pb-4">
                         <div class="mt-3 space-y-1 px-2">
                            <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Log in</a>
                            <a href="{{ route('register') }}" class="block rounded-md px-3 py-2 text-base font-medium text-blue-600 hover:bg-blue-50 hover:text-blue-700">Sign up</a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
             <!-- Flash Messages -->
            @if(session('success'))
                <div id="flash-success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-md shadow-sm flex justify-between items-start">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('flash-success').remove()" class="ml-4 text-green-400 hover:text-green-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error') || session('loginError'))
                <div id="flash-error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-md shadow-sm flex justify-between items-start">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') ?? session('loginError') }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('flash-error').remove()" class="ml-4 text-red-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Teman Jalan. All rights reserved.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-gray-500">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const openIcon = document.getElementById('menu-icon-open');
            const closeIcon = document.getElementById('menu-icon-close');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    openIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                });
            }

            // User dropdown toggle
            const userMenuBtn = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            
            if (userMenuBtn && userMenuDropdown) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }

            // Language dropdown toggle
            const langMenuBtn = document.getElementById('lang-menu-button');
            const langMenuDropdown = document.getElementById('lang-menu-dropdown');
            
            if (langMenuBtn && langMenuDropdown) {
                langMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    langMenuDropdown.classList.toggle('hidden');
                });

                 // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!langMenuBtn.contains(e.target) && !langMenuDropdown.contains(e.target)) {
                        langMenuDropdown.classList.add('hidden');
                    }
                });
            }

            // Auto-hide flash messages
            setTimeout(() => {
                const flashSuccess = document.getElementById('flash-success');
                const flashError = document.getElementById('flash-error');
                if (flashSuccess) {
                    flashSuccess.style.transition = 'opacity 1s';
                    flashSuccess.style.opacity = '0';
                    setTimeout(() => flashSuccess.remove(), 1000);
                }
                if (flashError) {
                    flashError.style.transition = 'opacity 1s';
                    flashError.style.opacity = '0';
                    setTimeout(() => flashError.remove(), 1000);
                }
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>