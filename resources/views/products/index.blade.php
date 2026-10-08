@extends('layouts.app')

@section('content')

<div class="bg-gray-50 min-h-screen">

    {{-- Header --}}

    <div class="bg-white border-b">

        <div class="max-w-7xl mx-auto px-4 py-8">

            <h1 class="text-3xl font-bold text-gray-900">
                Our Phones
            </h1>

            <p class="text-gray-500 mt-2">
                Find the perfect smartphone for you.
            </p>

        </div>

    </div>


    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Search & Filters --}}

        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="bg-white rounded-xl shadow-sm p-5 mb-8"
        >

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Search --}}

                <div class="md:col-span-1">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search phones..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 outline-none"
                    >

                </div>


                {{-- Category --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>

                    <select
                        name="category"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 outline-none"
                    >

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(request('category') == $category->id)
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Brand --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Brand
                    </label>

                    <select
                        name="brand"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 outline-none"
                    >

                        <option value="">
                            All Brands
                        </option>

                        @foreach($brands as $brand)

                            <option
                                value="{{ $brand->id }}"
                                @selected(request('brand') == $brand->id)
                            >
                                {{ $brand->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Sort --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sort By
                    </label>

                    <select
                        name="sort"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 outline-none"
                    >

                        <option value="">
                            Latest
                        </option>

                        <option
                            value="name"
                            @selected(request('sort') === 'name')
                        >
                            Name
                        </option>

                        <option
                            value="price_low"
                            @selected(request('sort') === 'price_low')
                        >
                            Price: Low to High
                        </option>

                        <option
                            value="price_high"
                            @selected(request('sort') === 'price_high')
                        >
                            Price: High to Low
                        </option>

                    </select>

                </div>

            </div>


            <div class="flex gap-3 mt-5">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold"
                >
                    Search
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold"
                >
                    Clear
                </a>

            </div>

        </form>


        {{-- Product Count --}}

        <div class="mb-5">

            <p class="text-gray-600">

                Showing
                <span class="font-semibold">
                    {{ $products->total() }}
                </span>
                products

            </p>

        </div>


        {{-- Products --}}

        @if($products->count())

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach($products as $product)

                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden">

                        {{-- Image --}}

                        <a href="{{ route('products.show', $product) }}">

                            <div class="h-56 bg-gray-100 flex items-center justify-center">

                                @if($product->image)

                                    <img
                                        src="{{ $product->image }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover"
                                    >

                                @else

                                    <div class="text-center">

                                        <div class="text-6xl">
                                            📱
                                        </div>

                                        <p class="text-gray-400 mt-2">
                                            No Image
                                        </p>

                                    </div>

                                @endif

                            </div>

                        </a>


                        {{-- Product Info --}}

                        <div class="p-5">

                            <div class="flex items-center justify-between mb-2">

                                <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    {{ $product->brand->name }}
                                </span>

                                @if($product->stock > 0)

                                    <span class="text-xs text-green-600 font-medium">
                                        In Stock
                                    </span>

                                @else

                                    <span class="text-xs text-red-600 font-medium">
                                        Out of Stock
                                    </span>

                                @endif

                            </div>


                            <h2 class="text-lg font-bold text-gray-900">

                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="hover:text-blue-600"
                                >
                                    {{ $product->name }}
                                </a>

                            </h2>


                            <p class="text-sm text-gray-500 mt-1">
                                {{ $product->category->name }}
                            </p>


                            <div class="flex items-center justify-between mt-5">

                                <span class="text-xl font-bold text-blue-600">
                                    ${{ number_format($product->price, 2) }}
                                </span>

                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="bg-gray-900 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition"
                                >
                                    View
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}

            <div class="mt-10">

                {{ $products->links() }}

            </div>

        @else

            <div class="bg-white rounded-xl p-12 text-center">

                <div class="text-6xl mb-4">
                    📱
                </div>

                <h2 class="text-xl font-bold text-gray-800">
                    No Products Found
                </h2>

                <p class="text-gray-500 mt-2">
                    Try changing your search or filter.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection