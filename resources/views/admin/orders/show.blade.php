@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Order Details
            </h1>

            <p class="text-gray-600 mt-1">
                {{ $order->order_number }}
            </p>

        </div>


        <a
            href="{{ route('admin.orders.index') }}"
            class="text-blue-600 hover:text-blue-800 font-semibold"
        >
            ← Back to Orders
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Errors --}}
    @if($errors->any())

        <div class="rounded-lg bg-red-50 border border-red-200 p-4">

            <ul class="list-disc list-inside text-red-700">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Customer --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <h2 class="text-xl font-bold text-gray-900 mb-5">
                    👤 Customer Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <p class="text-sm text-gray-500">
                            Name
                        </p>

                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $order->user->name }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Email
                        </p>

                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $order->user->email }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Phone
                        </p>

                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $order->phone }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Order Date
                        </p>

                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </p>

                    </div>

                </div>


                <div class="mt-5 pt-5 border-t border-gray-200">

                    <p class="text-sm text-gray-500">
                        Shipping Address
                    </p>

                    <p class="font-medium text-gray-900 mt-1">
                        {{ $order->shipping_address }}
                    </p>

                </div>


                @if($order->notes)

                    <div class="mt-5 pt-5 border-t border-gray-200">

                        <p class="text-sm text-gray-500">
                            Customer Notes
                        </p>

                        <p class="font-medium text-gray-900 mt-1">
                            {{ $order->notes }}
                        </p>

                    </div>

                @endif

            </div>


            {{-- Products --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <h2 class="text-xl font-bold text-gray-900 mb-5">
                    📱 Order Items
                </h2>


                <div class="divide-y divide-gray-200">

                    @foreach($order->items as $item)

                        <div class="py-5 first:pt-0 last:pb-0">

                            <div class="flex items-center justify-between gap-4">

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $item->product_name }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Quantity:
                                        {{ $item->quantity }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Unit Price:
                                        ${{ number_format($item->price, 2) }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="font-bold text-gray-900">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">

                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    ⚙️ Manage Order
                </h2>


                {{-- Update Form --}}
                <form
                    action="{{ route('admin.orders.update', $order) }}"
                    method="POST"
                >

                    @csrf

                    @method('PATCH')


                    {{-- Order Status --}}
                    <div class="mb-5">

                        <label
                            for="status"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Order Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option
                                value="pending"
                                @selected($order->status === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="processing"
                                @selected($order->status === 'processing')
                            >
                                Processing
                            </option>

                            <option
                                value="shipped"
                                @selected($order->status === 'shipped')
                            >
                                Shipped
                            </option>

                            <option
                                value="delivered"
                                @selected($order->status === 'delivered')
                            >
                                Delivered
                            </option>

                            <option
                                value="cancelled"
                                @selected($order->status === 'cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Payment Status --}}
                    <div class="mb-6">

                        <label
                            for="payment_status"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Payment Status
                        </label>

                        <select
                            id="payment_status"
                            name="payment_status"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option
                                value="pending"
                                @selected($order->payment_status === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="paid"
                                @selected($order->payment_status === 'paid')
                            >
                                Paid
                            </option>

                            <option
                                value="failed"
                                @selected($order->payment_status === 'failed')
                            >
                                Failed
                            </option>

                            <option
                                value="refunded"
                                @selected($order->payment_status === 'refunded')
                            >
                                Refunded
                            </option>

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-lg"
                    >
                        💾 Update Order
                    </button>

                </form>


                {{-- Order Summary --}}
                <div class="mt-8 pt-6 border-t border-gray-200">

                    <h3 class="font-bold text-gray-900 mb-4">
                        Order Summary
                    </h3>


                    <div class="space-y-3">

                        <div class="flex justify-between text-gray-600">

                            <span>
                                Subtotal
                            </span>

                            <span>
                                ${{ number_format($order->subtotal, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between text-gray-600">

                            <span>
                                Shipping
                            </span>

                            <span class="text-green-600">
                                Free
                            </span>

                        </div>


                        <div class="border-t border-gray-200 pt-3 flex justify-between">

                            <span class="font-bold text-gray-900">
                                Total
                            </span>

                            <span class="text-xl font-bold text-blue-600">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Payment Method --}}
                <div class="mt-6 pt-6 border-t border-gray-200">

                    <p class="text-sm text-gray-500">
                        Payment Method
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        Cash on Delivery
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection