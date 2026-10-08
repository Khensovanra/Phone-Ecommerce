@extends('layouts.app')

@section('content')

{{-- Hero Section --}}

<section class="bg-gray-900 text-white">

    <div class="max-w-7xl mx-auto px-4 py-20">

        <div class="max-w-3xl">

            <p class="text-blue-400 font-semibold mb-3">
                PHONE E-COMMERCE
            </p>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight">

                Find Your
                <span class="text-blue-400">
                    Perfect Phone
                </span>

            </h1>

            <p class="text-gray-300 text-lg mt-6 max-w-2xl">

                Discover the latest smartphones from the world's most popular
                brands at great prices.

            </p>


            <div class="flex flex-wrap gap-4 mt-8">

                <a
                    href="{{ route('products.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 px-7 py-3 rounded-lg font-semibold"
                >
                    Shop Now
                </a>

                @guest

                    <a
                        href="{{ route('register') }}"
                        class="border border-gray-500 hover:bg-gray-800 px-7 py-3 rounded-lg font-semibold"
                    >
                        Create Account
                    </a>

                @endguest

            </div>

        </div>

    </div>

</section>


{{-- Categories --}}

<section class="max-w-7xl mx-auto px-4 py-14">

    <div class="flex items-center justify-between mb-7">

        <div>

            <h2 class="text-3xl font-bold text-gray-900">
                Categories
            </h2>

            <p class="text-gray-500 mt-1">
                Browse phones by category
            </p>

        </div>

        <a
            href="{{ route('products.index') }}"
            class="text-blue-600 font-semibold"
        >
            View All →
        </a>

    </div>


    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

        @foreach($categories as $category)

            <a
                href="{{ route('products.index', ['category' => $category->id]) }}"
                class="bg-white border rounded-xl p-6 text-center hover:shadow-md hover:border-blue-300 transition"
            >

                <div class="text-4xl mb-3">
                    📱
                </div>

                <h3 class="font-semibold text-gray-800">
                    {{ $category->name }}
                </h3>

            </a>

        @endforeach

    </div>

</section>


{{-- Featured Products --}}

<section class="bg-gray-50">

    <div class="max-w-7xl mx-auto px-4 py-14">

        <div class="flex items-center justify-between mb-7">

            <div>

                <h2 class="text-3xl font-bold text-gray-900">
                    Featured Phones
                </h2>

                <p class="text-gray-500 mt-1">
                    Check out our latest products
                </p>

            </div>

            <a
                href="{{ route('products.index') }}"
                class="text-blue-600 font-semibold"
            >
                View All →
            </a>

        </div>


        @if($featuredProducts->count())

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach($featuredProducts as $product)

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg overflow-hidden transition">

                        <a href="{{ route('products.show', $product) }}">

                            <div class="h-56 bg-gray-100 flex items-center justify-center">

                                @if($product->image)

                                    <img
                                        src="{{ $product->image }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover"
                                    >

                                @else

                                    <span class="text-7xl">
                                        📱
                                    </span>

                                @endif

                            </div>

                        </a>


                        <div class="p-5">

                            <p class="text-xs text-blue-600 font-bold uppercase">
                                {{ $product->brand->name }}
                            </p>

                            <h3 class="text-lg font-bold text-gray-900 mt-1">

                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="hover:text-blue-600"
                                >
                                    {{ $product->name }}
                                </a>

                            </h3>

                            <div class="flex items-center justify-between mt-4">

                                <span class="text-xl font-bold text-blue-600">
                                    ${{ number_format($product->price, 2) }}
                                </span>

                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="bg-gray-900 text-white px-3 py-2 rounded-lg text-sm"
                                >
                                    View
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-white rounded-xl p-12 text-center">

                <p class="text-gray-500">
                    No products available yet.
                </p>

            </div>

        @endif

    </div>

</section>


{{-- Brands --}}

<section class="max-w-7xl mx-auto px-4 py-14">

    <div class="text-center mb-8">

        <h2 class="text-3xl font-bold text-gray-900">
            Popular Brands
        </h2>

        <p class="text-gray-500 mt-2">
            Shop from trusted phone brands
        </p>

    </div>


    <div class="flex flex-wrap justify-center gap-4">

        @foreach($brands as $brand)

            <a
                href="{{ route('products.index', ['brand' => $brand->id]) }}"
                class="px-8 py-4 bg-white border rounded-xl font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600 transition"
            >
                {{ $brand->name }}
            </a>

        @endforeach

    </div>

</section>

@endsection