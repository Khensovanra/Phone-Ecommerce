<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white p-6">

            <h1 class="text-2xl font-bold mb-8">
                Admin Panel
            </h1>

            <nav class="space-y-2">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                {{-- Products --}}
                <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '/admin/products' }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>📱</span>
                    <span>Products</span>
                </a>

                {{-- Categories --}}
                <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '/admin/categories' }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>📂</span>
                    <span>Categories</span>
                </a>

                {{-- Brands --}}
                <a href="{{ Route::has('admin.brands.index') ? route('admin.brands.index') : '/admin/brands' }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.brands.*') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>🏷️</span>
                    <span>Brands</span>
                </a>

                {{-- Orders --}}
                <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '/admin/orders' }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>📦</span>
                    <span>Orders</span>
                </a>

                {{-- Users --}}
                <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '/admin/users' }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-800 text-gray-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 font-semibold' : '' }}">
                    <span>👥</span>
                    <span>Users</span>
                </a>

            </nav>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">

            @yield('content')

        </main>

    </div>

</body>
</html>