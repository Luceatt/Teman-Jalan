<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Teman Jalan')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #555;
        }
        .back:hover {
            color: black;
        }
        h1, h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    {{-- Bagian konten utama tiap halaman --}}
    @yield('content')
</body>

</html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Teman Jalan') - Place Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery (for AJAX functionality) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Places JavaScript -->
    <script>
        window.routes = {
            placesIndex: '{{ route("places.index") }}',
            placesSearch: '{{ route("places.search") }}',
            placesNearby: '{{ route("places.nearby") }}'
        };
    </script>
    @vite(['resources/js/places.js'])

    <!-- Custom styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('places.index') }}" class="flex items-center">
                            <i class="fas fa-map-marked-alt text-blue-600 text-2xl mr-3"></i>
                            <span class="font-bold text-xl text-gray-900">Teman Jalan</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('places.index') }}"
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('places.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-map-marker-alt mr-2"></i>Tempat
                        </a>
                        <a href="{{ route('rundowns.index') }}"
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('rundowns.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-clipboard-list mr-2"></i>Rundown
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button type="button" id="mobile-menu-button"
                                class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="md:hidden hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                        <a href="{{ route('places.index') }}"
                           class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('places.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-map-marker-alt mr-2"></i>Tempat
                        </a>
                        <a href="{{ route('rundowns.index') }}"
                           class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('rundowns.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-clipboard-list mr-2"></i>Rundown
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            <!-- Flash Messages -->
            @if(session('success'))
                <div id="success-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button onclick="document.getElementById('success-message').remove()"
                                class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div id="error-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button onclick="document.getElementById('error-message').remove()"
                                class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} Teman Jalan - Place Management System. Built with Laravel.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.getElementById('success-message')?.remove();
            document.getElementById('error-message')?.remove();
        }, 5000);

        // Global notification function
        window.showNotification = function(message, type = 'info') {
            const colors = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                warning: 'bg-yellow-600',
                info: 'bg-blue-600'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${colors[type]}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        };

        // Confirm dialog for destructive actions
        window.confirmAction = function(message, callback) {
            if (confirm(message)) {
                callback();
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
