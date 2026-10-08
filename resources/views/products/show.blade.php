@extends('layouts.app')

@section('content')

<div class="bg-gray-50 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Breadcrumb --}}

        <div class="mb-8">

            <a
                href="{{ route('products.index') }}"
                class="text-blue-600 hover:text-blue-700"
            >
                ← Back to Products
            </a>

        </div>


        {{-- Product Details --}}

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-2">

                {{-- Product Image --}}

                <div class="bg-gray-100 min-h-[500px] flex items-center justify-center">

                    @if($product->image)

                        <img
                            src="{{ $product->image }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full max-h-[500px] object-contain"
                        >

                    @else

                        <div class="text-center">

                            <div class="text-9xl">
                                📱
                            </div>

                            <p class="text-gray-400 mt-4">
                                Product Image
                            </p>

                        </div>

                    @endif

                </div>


                {{-- Information --}}

                <div class="p-8 md:p-12">

                    {{-- Brand --}}

                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">

                        {{ $product->brand->name }}

                    </span>


                    {{-- Name --}}

                    <h1 class="text-4xl font-bold text-gray-900 mt-5">

                        {{ $product->name }}

                    </h1>


                    {{-- Category --}}

                    <p class="text-gray-500 mt-2">

                        Category:
                        <span class="font-medium">
                            {{ $product->category->name }}
                        </span>

                    </p>


                    {{-- Price --}}

                    <div class="mt-8">

                        <span class="text-4xl font-bold text-blue-600">

                            ${{ number_format($product->price, 2) }}

                        </span>

                    </div>


                    {{-- Description --}}

                    <div class="mt-8">

                        <h2 class="text-lg font-bold text-gray-900">
                            Description
                        </h2>

                        <p class="text-gray-600 leading-7 mt-2">

                            {{ $product->description ?: 'No description available.' }}

                        </p>

                    </div>


                    {{-- Product Information --}}

                    <div class="mt-8 border-t pt-6">

                        <div class="grid grid-cols-2 gap-4">

                            <div>

                                <p class="text-sm text-gray-500">
                                    SKU
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $product->sku }}
                                </p>

                            </div>


                            <div>

                                <p class="text-sm text-gray-500">
                                    Stock
                                </p>

                                <p class="font-semibold
                                    {{ $product->stock > 0
                                        ? 'text-green-600'
                                        : 'text-red-600'
                                    }}"
                                >

                                    {{ $product->stock }} available

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Add to Cart --}}

                    <div class="mt-8">

                        @if($product->stock > 0)

                            @auth

                                <form
                                    method="POST"
                                    action="{{ route('cart.add', $product) }}"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold text-lg transition"
                                    >
                                        🛒 Add to Cart
                                    </button>

                                </form>

                            @else

                                <a
                                    href="{{ route('login') }}"
                                    class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold text-lg transition"
                                >
                                    Login to Buy
                                </a>

                            @endauth

                        @else

                            <button
                                disabled
                                class="w-full bg-gray-300 text-gray-500 py-4 rounded-xl font-bold text-lg cursor-not-allowed"
                            >
                                Out of Stock
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Related Products --}}

        @if($relatedProducts->count())

            <div class="mt-12">

                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Related Products
                </h2>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    @foreach($relatedProducts as $related)

                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                            <a href="{{ route('products.show', $related) }}">

                                <div class="h-48 bg-gray-100 flex items-center justify-center">

                                    @if($related->image)

                                        <img
                                            src="{{ $related->image }}"
                                            alt="{{ $related->name }}"
                                            class="w-full h-full object-cover"
                                        >

                                    @else

                                        <span class="text-6xl">
                                            📱
                                        </span>

                                    @endif

                                </div>

                            </a>


                            <div class="p-4">

                                <p class="text-xs text-blue-600 font-semibold">
                                    {{ $related->brand->name }}
                                </p>

                                <h3 class="font-bold text-gray-900 mt-1">
                                    {{ $related->name }}
                                </h3>

                                <p class="text-blue-600 font-bold mt-3">
                                    ${{ number_format($related->price, 2) }}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>

</div>

@endsection