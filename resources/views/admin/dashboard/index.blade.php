@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Header --}}

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Admin Dashboard
        </h1>

        <p class="text-gray-500 mt-1">
            Overview of your phone e-commerce system.
        </p>

    </div>


    {{-- Main Statistics --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Products --}}

        <div class="bg-white rounded-xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Products
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $totalProducts }}
                    </p>

                </div>

                <div class="text-3xl">
                    📱
                </div>

            </div>

        </div>


        {{-- Categories --}}

        <div class="bg-white rounded-xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Categories
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $totalCategories }}
                    </p>

                </div>

                <div class="text-3xl">
                    📂
                </div>

            </div>

        </div>


        {{-- Orders --}}

        <div class="bg-white rounded-xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Orders
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $totalOrders }}
                    </p>

                </div>

                <div class="text-3xl">
                    📦
                </div>

            </div>

        </div>


        {{-- Users --}}

        <div class="bg-white rounded-xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Users
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $totalUsers }}
                    </p>

                </div>

                <div class="text-3xl">
                    👥
                </div>

            </div>

        </div>

    </div>


    {{-- Revenue Statistics --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl shadow-sm border p-6">

            <p class="text-sm text-gray-500">
                Today's Orders
            </p>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $todayOrders }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-6">

            <p class="text-sm text-gray-500">
                Today's Revenue
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                ${{ number_format($todayRevenue, 2) }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-6">

            <p class="text-sm text-gray-500">
                Total Revenue
            </p>

            <p class="text-3xl font-bold text-purple-600 mt-2">
                ${{ number_format($totalRevenue, 2) }}
            </p>

        </div>

    </div>


    {{-- Order Status --}}

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-gray-500">
                Pending
            </p>

            <p class="text-2xl font-bold text-yellow-600 mt-1">
                {{ $pendingOrders }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-gray-500">
                Processing
            </p>

            <p class="text-2xl font-bold text-blue-600 mt-1">
                {{ $processingOrders }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-gray-500">
                Shipped
            </p>

            <p class="text-2xl font-bold text-purple-600 mt-1">
                {{ $shippedOrders }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-gray-500">
                Delivered
            </p>

            <p class="text-2xl font-bold text-green-600 mt-1">
                {{ $deliveredOrders }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-gray-500">
                Cancelled
            </p>

            <p class="text-2xl font-bold text-red-600 mt-1">
                {{ $cancelledOrders }}
            </p>

        </div>

    </div>


    {{-- Recent Orders --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Recent Orders
                </h2>

                <p class="text-sm text-gray-500">
                    Latest customer orders.
                </p>

            </div>


            <a
                href="{{ route('admin.orders.index') }}"
                class="text-blue-600 hover:text-blue-800 font-medium text-sm"
            >
                View All
            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm">
                            Order
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Customer
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Total
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Date
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($recentOrders as $order)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <a
                                    href="{{ route('admin.orders.show', $order) }}"
                                    class="font-semibold text-blue-600 hover:text-blue-800"
                                >
                                    {{ $order->order_number }}
                                </a>

                            </td>


                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $order->user->name ?? 'Unknown' }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $order->user->email ?? '-' }}
                                </div>

                            </td>


                            <td class="px-6 py-4 font-semibold">

                                ${{ number_format(
                                    $order->total_amount,
                                    2
                                ) }}

                            </td>


                            <td class="px-6 py-4">

                                @php

                                    $statusClasses = [
                                        'pending' =>
                                            'bg-yellow-100 text-yellow-700',

                                        'processing' =>
                                            'bg-blue-100 text-blue-700',

                                        'shipped' =>
                                            'bg-purple-100 text-purple-700',

                                        'delivered' =>
                                            'bg-green-100 text-green-700',

                                        'cancelled' =>
                                            'bg-red-100 text-red-700',
                                    ];

                                @endphp


                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}"
                                >
                                    {{ ucfirst($order->status) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $order->created_at->format('M d, Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No orders yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Low Stock --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Low Stock Products
                </h2>

                <p class="text-sm text-gray-500">
                    Products with 5 or fewer units.
                </p>

            </div>


            <a
                href="{{ route('admin.products.index') }}"
                class="text-blue-600 hover:text-blue-800 font-medium text-sm"
            >
                View Products
            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm">
                            Product
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Category
                        </th>

                        <th class="px-6 py-4 text-left text-sm">
                            Brand
                        </th>

                        <th class="px-6 py-4 text-center text-sm">
                            Stock
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($lowStockProductsList as $product)

                        <tr>

                            <td class="px-6 py-4 font-medium">
                                {{ $product->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->brand->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $product->stock <= 0
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-orange-100 text-orange-700'
                                    }}"
                                >
                                    {{ $product->stock }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No low-stock products.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection