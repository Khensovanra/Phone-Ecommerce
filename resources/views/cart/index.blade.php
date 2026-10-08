@extends('layouts.app')

@section('content')

<div class="bg-gray-50 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Page Header --}}

        <div class="mb-8">

            <h1 class="text-3xl font-bold text-gray-900">
                Shopping Cart
            </h1>

            <p class="text-gray-500 mt-2">
                Review your selected products before checkout.
            </p>

        </div>


        {{-- Messages --}}

        @if(session('success'))

            <div class="mb-6 rounded-lg bg-green-100 border border-green-200 text-green-700 px-5 py-4">

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 rounded-lg bg-red-100 border border-red-200 text-red-700 px-5 py-4">

                {{ session('error') }}

            </div>

        @endif


        {{-- Validation Errors --}}

        @if($errors->any())

            <div class="mb-6 rounded-lg bg-red-100 border border-red-200 text-red-700 px-5 py-4">

                <ul class="list-disc list-inside">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Empty Cart --}}

        @if($cart->items->isEmpty())

            <div class="bg-white rounded-2xl shadow-sm p-16 text-center">

                <div class="text-7xl mb-6">
                    🛒
                </div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Your Cart is Empty
                </h2>

                <p class="text-gray-500 mt-3">
                    You haven't added any products yet.
                </p>

                <a
                    href="{{ route('products.index') }}"
                    class="inline-block mt-7 bg-blue-600 hover:bg-blue-700 text-white px-7 py-3 rounded-lg font-semibold"
                >
                    Start Shopping
                </a>

            </div>

        @else

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Cart Items --}}

                <div class="lg:col-span-2 space-y-4">

                    @foreach($cart->items as $item)

                        @php

                            $itemSubtotal =
                                $item->product->price * $item->quantity;

                        @endphp

                        <div class="bg-white rounded-2xl shadow-sm p-5">

                            <div class="flex flex-col sm:flex-row gap-5">

                                {{-- Image --}}

                                <a
                                    href="{{ route('products.show', $item->product) }}"
                                    class="w-full sm:w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0"
                                >

                                    @if($item->product->image)

                                        <img
                                            src="{{ $item->product->image }}"
                                            alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover"
                                        >

                                    @else

                                        <span class="text-5xl">
                                            📱
                                        </span>

                                    @endif

                                </a>


                                {{-- Product Information --}}

                                <div class="flex-1">

                                    <div class="flex justify-between gap-4">

                                        <div>

                                            <p class="text-sm text-blue-600 font-semibold">
                                                {{ $item->product->brand->name }}
                                            </p>

                                            <h2 class="text-lg font-bold text-gray-900 mt-1">

                                                <a
                                                    href="{{ route('products.show', $item->product) }}"
                                                    class="hover:text-blue-600"
                                                >
                                                    {{ $item->product->name }}
                                                </a>

                                            </h2>

                                            <p class="text-sm text-gray-500 mt-1">
                                                SKU: {{ $item->product->sku }}
                                            </p>

                                        </div>


                                        {{-- Remove --}}

                                        <form
                                            method="POST"
                                            action="{{ route('cart.remove', $item) }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    </div>


                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6">

                                        {{-- Price --}}

                                        <div>

                                            <p class="text-sm text-gray-500">
                                                Price
                                            </p>

                                            <p class="font-semibold text-gray-800">
                                                ${{ number_format($item->product->price, 2) }}
                                            </p>

                                        </div>


                                        {{-- Quantity --}}

                                        <form
                                            method="POST"
                                            action="{{ route('cart.update', $item) }}"
                                            class="flex items-end gap-3"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <div>

                                                <label class="block text-sm text-gray-500 mb-1">
                                                    Quantity
                                                </label>

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    max="{{ $item->product->stock }}"
                                                    class="w-24 border border-gray-300 rounded-lg px-3 py-2 text-center"
                                                >

                                            </div>

                                            <button
                                                type="submit"
                                                class="bg-gray-900 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold"
                                            >
                                                Update
                                            </button>

                                        </form>


                                        {{-- Subtotal --}}

                                        <div class="sm:text-right">

                                            <p class="text-sm text-gray-500">
                                                Subtotal
                                            </p>

                                            <p class="text-xl font-bold text-blue-600">
                                                ${{ number_format($itemSubtotal, 2) }}
                                            </p>

                                        </div>

                                    </div>


                                    {{-- Stock --}}

                                    <p class="text-xs text-gray-400 mt-4">

                                        {{ $item->product->stock }}
                                        units available

                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach


                    {{-- Continue Shopping --}}

                    <div>

                        <a
                            href="{{ route('products.index') }}"
                            class="text-blue-600 hover:text-blue-700 font-semibold"
                        >
                            ← Continue Shopping
                        </a>

                    </div>

                </div>


                {{-- Order Summary --}}

                <div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6">

                        <h2 class="text-xl font-bold text-gray-900">
                            Order Summary
                        </h2>


                        <div class="border-t mt-5 pt-5 space-y-4">

                            {{-- Subtotal --}}

                            <div class="flex justify-between">

                                <span class="text-gray-600">
                                    Subtotal
                                </span>

                                <span class="font-semibold">
                                    ${{ number_format($subtotal, 2) }}
                                </span>

                            </div>


                            {{-- Shipping --}}

                            <div class="flex justify-between">

                                <span class="text-gray-600">
                                    Shipping
                                </span>

                                <span class="font-semibold text-green-600">
                                    Free
                                </span>

                            </div>


                            {{-- Total --}}

                            <div class="border-t pt-4 flex justify-between">

                                <span class="text-lg font-bold">
                                    Total
                                </span>

                                <span class="text-2xl font-bold text-blue-600">
                                    ${{ number_format($subtotal, 2) }}
                                </span>

                            </div>

                        </div>


                        {{-- Checkout --}}

                        <a
                            href="{{ route('checkout.index') }}"
                            class="block text-center mt-7 w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-bold transition"
                        >
                            Proceed to Checkout
                        </a>


                        <div class="text-center mt-4">

                            <p class="text-xs text-gray-400">
                                Secure checkout
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection