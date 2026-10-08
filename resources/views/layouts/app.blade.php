<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Phone Store') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Top Navigation Bar --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                {{-- Store Brand / Logo --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                        Phone Store
                    </a>

                    {{-- Main Navigation Links --}}
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Home
                        </a>
                        @if(Route::has('products.index'))
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                Products
                            </a>
                        @endif
                    </div>
                </div>

                {{-- User, Orders & Cart Actions --}}
                <div class="flex items-center gap-6">
                    @auth
                        @if(Route::has('cart.index'))
                            <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                🛒 Cart
                            </a>
                        @endif

                        @if(Route::has('orders.index'))
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                📦 My Orders
                            </a>
                        @endif

                        @if(auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                ⚙️ Admin Panel
                            </a>
                        @endif

                        <span class="text-gray-500 text-sm">
                            {{ auth()->user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Login
                        </a>

                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition">
                            Register
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Page Body Injected Here --}}
    <main class="flex-grow">
        @yield('content')
    </main>

</body>
</html>