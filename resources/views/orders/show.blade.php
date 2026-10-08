@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Order Details
            </h1>

            <p class="text-gray-600 mt-2">
                {{ $order->order_number }}
            </p>

        </div>


        <a
            href="{{ route('orders.index') }}"
            class="text-blue-600 hover:text-blue-800 font-semibold"
        >
            ← Back to Orders
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    📦 Ordered Products
                </h2>


                <div class="divide-y divide-gray-200">

                    @foreach($order->items as $item)

                        <div class="py-5 first:pt-0 last:pb-0">

                            <div class="flex justify-between gap-4">

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $item->product_name }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Quantity:
                                        {{ $item->quantity }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Price:
                                        ${{ number_format($item->price, 2) }}
                                    </p>

                                </div>


                                <div class="font-bold text-gray-900">

                                    ${{ number_format(
                                        $item->subtotal,
                                        2
                                    ) }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- Shipping --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                <h2 class="text-xl font-bold text-gray-900 mb-5">
                    📍 Shipping Information
                </h2>


                <div class="space-y-4">

                    <div>

                        <p class="text-sm text-gray-500">
                            Phone
                        </p>

                        <p class="font-medium text-gray-900">
                            {{ $order->phone }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Address
                        </p>

                        <p class="font-medium text-gray-900">
                            {{ $order->shipping_address }}
                        </p>

                    </div>


                    @if($order->notes)

                        <div>

                            <p class="text-sm text-gray-500">
                                Notes
                            </p>

                            <p class="font-medium text-gray-900">
                                {{ $order->notes }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">

                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    Order Summary
                </h2>


                {{-- Status --}}
                <div class="mb-6">

                    <p class="text-sm text-gray-500 mb-2">
                        Order Status
                    </p>

                    @if($order->status === 'pending')

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            Pending
                        </span>

                    @elseif($order->status === 'processing')

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            Processing
                        </span>

                    @elseif($order->status === 'shipped')

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                            Shipped
                        </span>

                    @elseif($order->status === 'delivered')

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Delivered
                        </span>

                    @elseif($order->status === 'cancelled')

                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            Cancelled
                        </span>

                    @endif

                </div>


                <div class="border-t border-gray-200 pt-5 space-y-3">

                    <div class="flex justify-between text-gray-600">

                        <span>
                            Subtotal
                        </span>

                        <span>
                            ${{ number_format(
                                $order->subtotal,
                                2
                            ) }}
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


                    <div class="border-t border-gray-200 pt-4 flex justify-between">

                        <span class="text-lg font-bold">
                            Total
                        </span>

                        <span class="text-2xl font-bold text-blue-600">
                            ${{ number_format(
                                $order->total_amount,
                                2
                            ) }}
                        </span>

                    </div>

                </div>


                <div class="mt-6 pt-6 border-t border-gray-200">

                    <p class="text-sm text-gray-500">
                        Payment Method
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        Cash on Delivery
                    </p>

                </div>


                <div class="mt-4">

                    <p class="text-sm text-gray-500">
                        Order Date
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection