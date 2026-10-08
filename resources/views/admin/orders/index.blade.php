@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Orders
            </h1>

            <p class="text-gray-600 mt-1">
                Manage customer orders and payments.
            </p>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Orders --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

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


        {{-- Pending --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Pending
                    </p>

                    <p class="text-3xl font-bold text-yellow-600 mt-2">
                        {{ $pendingOrders }}
                    </p>

                </div>

                <div class="text-3xl">
                    ⏳
                </div>

            </div>

        </div>


        {{-- Processing --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Processing
                    </p>

                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $processingOrders }}
                    </p>

                </div>

                <div class="text-3xl">
                    🔄
                </div>

            </div>

        </div>


        {{-- Delivered --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Delivered
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $completedOrders }}
                    </p>

                </div>

                <div class="text-3xl">
                    ✅
                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

        <form
            action="{{ route('admin.orders.index') }}"
            method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Order number, customer, email, phone..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Order Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="pending"
                        @selected(request('status') === 'pending')
                    >
                        Pending
                    </option>

                    <option
                        value="processing"
                        @selected(request('status') === 'processing')
                    >
                        Processing
                    </option>

                    <option
                        value="shipped"
                        @selected(request('status') === 'shipped')
                    >
                        Shipped
                    </option>

                    <option
                        value="delivered"
                        @selected(request('status') === 'delivered')
                    >
                        Delivered
                    </option>

                    <option
                        value="cancelled"
                        @selected(request('status') === 'cancelled')
                    >
                        Cancelled
                    </option>

                </select>

            </div>


            {{-- Payment Status --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Payment
                </label>

                <select
                    name="payment_status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        All Payments
                    </option>

                    <option
                        value="pending"
                        @selected(request('payment_status') === 'pending')
                    >
                        Pending
                    </option>

                    <option
                        value="paid"
                        @selected(request('payment_status') === 'paid')
                    >
                        Paid
                    </option>

                    <option
                        value="failed"
                        @selected(request('payment_status') === 'failed')
                    >
                        Failed
                    </option>

                    <option
                        value="refunded"
                        @selected(request('payment_status') === 'refunded')
                    >
                        Refunded
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="md:col-span-4 flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg"
                >
                    🔍 Search
                </button>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-lg"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        @if($orders->count())

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Order
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Customer
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Total
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Order Status
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Payment
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Date
                            </th>

                            <th class="text-right px-6 py-4 text-sm font-semibold text-gray-700">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @foreach($orders as $order)

                            <tr class="hover:bg-gray-50">

                                {{-- Order --}}
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-gray-900">
                                        {{ $order->order_number }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        #{{ $order->id }}
                                    </div>

                                </td>


                                {{-- Customer --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $order->user->name }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $order->user->email }}
                                    </div>

                                </td>


                                {{-- Total --}}
                                <td class="px-6 py-4">

                                    <span class="font-semibold text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @if($order->status === 'pending')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>

                                    @elseif($order->status === 'processing')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            Processing
                                        </span>

                                    @elseif($order->status === 'shipped')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Shipped
                                        </span>

                                    @elseif($order->status === 'delivered')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Delivered
                                        </span>

                                    @elseif($order->status === 'cancelled')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Cancelled
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment --}}
                                <td class="px-6 py-4">

                                    @if($order->payment_status === 'paid')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Paid
                                        </span>

                                    @elseif($order->payment_status === 'failed')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Failed
                                        </span>

                                    @elseif($order->payment_status === 'refunded')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Refunded
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- Date --}}
                                <td class="px-6 py-4 text-gray-600">

                                    {{ $order->created_at->format('M d, Y') }}

                                    <div class="text-xs text-gray-400">
                                        {{ $order->created_at->format('H:i') }}
                                    </div>

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="p-5 border-t border-gray-200">

                {{ $orders->links() }}

            </div>

        @else

            <div class="p-12 text-center">

                <div class="text-6xl mb-4">
                    📦
                </div>

                <h2 class="text-xl font-bold text-gray-900">
                    No orders found
                </h2>

                <p class="text-gray-500 mt-2">
                    There are no orders matching your search.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection