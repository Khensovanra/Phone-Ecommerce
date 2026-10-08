@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Checkout
        </h1>

        <p class="mt-2 text-gray-600">
            Complete your information and place your order.
        </p>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">

            <h3 class="font-semibold text-red-800 mb-2">
                Please fix the following errors:
            </h3>

            <ul class="list-disc list-inside text-red-700 space-y-1">

                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>
    @endif


    <form
        action="{{ route('checkout.store') }}"
        method="POST"
    >

        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT SIDE --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Shipping Information --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        📍 Shipping Information
                    </h2>


                    {{-- Address --}}
                    <div class="mb-5">

                        <label
                            for="shipping_address"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Shipping Address
                        </label>

                        <textarea
                            id="shipping_address"
                            name="shipping_address"
                            rows="4"
                            required
                            placeholder="Enter your full shipping address..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('shipping_address') }}</textarea>

                    </div>


                    {{-- Phone --}}
                    <div>

                        <label
                            for="phone"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Phone Number
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            placeholder="Enter your phone number"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>

                </div>


                {{-- Payment --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        💳 Payment Method
                    </h2>


                    <label
                        class="flex items-center gap-4 border border-blue-500 bg-blue-50 rounded-xl p-5 cursor-pointer"
                    >

                        <input
                            type="radio"
                            name="payment_method"
                            value="cash_on_delivery"
                            checked
                            class="w-5 h-5 text-blue-600"
                        >

                        <div>

                            <div class="font-semibold text-gray-900">
                                Cash on Delivery
                            </div>

                            <div class="text-sm text-gray-600 mt-1">
                                Pay when your order is delivered.
                            </div>

                        </div>

                    </label>

                </div>


                {{-- Notes --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        📝 Additional Notes
                    </h2>

                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="Any special instructions? (Optional)"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">

                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        🛒 Order Summary
                    </h2>


                    {{-- Products --}}
                    <div class="space-y-4 mb-6">

                        @foreach($cart->items as $item)

                            <div class="flex gap-3">

                                {{-- Product Image --}}
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">

                                    @if($item->product->image)

                                        <img
                                            src="{{ asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover rounded-lg"
                                        >

                                    @else

                                        <span class="text-2xl">
                                            📱
                                        </span>

                                    @endif

                                </div>


                                {{-- Product Information --}}
                                <div class="flex-1 min-w-0">

                                    <h3 class="font-semibold text-gray-900 truncate">
                                        {{ $item->product->name }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        Qty: {{ $item->quantity }}
                                    </p>

                                    <p class="text-sm font-medium text-gray-700">
                                        ${{ number_format($item->product->price, 2) }}
                                        ×
                                        {{ $item->quantity }}
                                    </p>

                                </div>


                                {{-- Item Total --}}
                                <div class="font-semibold text-gray-900">

                                    ${{ number_format(
                                        $item->product->price * $item->quantity,
                                        2
                                    ) }}

                                </div>

                            </div>

                        @endforeach

                    </div>


                    <div class="border-t border-gray-200 pt-5 space-y-3">

                        {{-- Subtotal --}}
                        <div class="flex justify-between text-gray-600">

                            <span>
                                Subtotal
                            </span>

                            <span>
                                ${{ number_format($subtotal, 2) }}
                            </span>

                        </div>


                        {{-- Shipping --}}
                        <div class="flex justify-between text-gray-600">

                            <span>
                                Shipping
                            </span>

                            <span class="text-green-600 font-medium">
                                Free
                            </span>

                        </div>


                        {{-- Total --}}
                        <div class="border-t border-gray-200 pt-4 flex justify-between">

                            <span class="text-lg font-bold text-gray-900">
                                Total
                            </span>

                            <span class="text-2xl font-bold text-blue-600">
                                ${{ number_format($total, 2) }}
                            </span>

                        </div>

                    </div>


                    {{-- Place Order --}}
                    <button
                        type="submit"
                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition"
                    >
                        ✅ Place Order
                    </button>


                    {{-- Back --}}
                    <a
                        href="{{ route('cart.index') }}"
                        class="block text-center mt-4 text-gray-600 hover:text-blue-600 font-medium"
                    >
                        ← Back to Cart
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection