@extends('admin.layouts.app')

@section('title', $product->name)

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Product Details
            </h1>

            <p class="text-gray-600 mt-1">
                {{ $product->name }}
            </p>

        </div>


        <div class="flex gap-3">

            <a
                href="{{ route('admin.products.edit', $product) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg"
            >
                ✏️ Edit
            </a>

            <a
                href="{{ route('admin.products.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-3 rounded-lg"
            >
                ← Back
            </a>

        </div>

    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Image --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <div class="aspect-square bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">

                @if($product->image)

                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover"
                    >

                @else

                    <span class="text-8xl">
                        📱
                    </span>

                @endif

            </div>

        </div>


        {{-- Information --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <h2 class="text-2xl font-bold text-gray-900">
                {{ $product->name }}
            </h2>


            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>

                    <p class="text-sm text-gray-500">
                        Brand
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $product->brand->name }}
                    </p>

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        Category
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $product->category->name }}
                    </p>

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        SKU
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $product->sku }}
                    </p>

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        Slug
                    </p>

                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $product->slug }}
                    </p>

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        Price
                    </p>

                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        ${{ number_format($product->price, 2) }}
                    </p>

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        Stock
                    </p>

                    @if($product->stock === 0)

                        <p class="font-bold text-red-600 mt-1">
                            Out of Stock
                        </p>

                    @elseif($product->stock <= 5)

                        <p class="font-bold text-orange-600 mt-1">
                            {{ $product->stock }} units
                        </p>

                    @else

                        <p class="font-bold text-green-600 mt-1">
                            {{ $product->stock }} units
                        </p>

                    @endif

                </div>


                <div>

                    <p class="text-sm text-gray-500">
                        Status
                    </p>

                    @if($product->is_active)

                        <span class="inline-flex mt-2 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Active
                        </span>

                    @else

                        <span class="inline-flex mt-2 px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>


            {{-- Description --}}
            @if($product->description)

                <div class="mt-8 pt-6 border-t border-gray-200">

                    <h3 class="font-bold text-gray-900 mb-3">
                        Description
                    </h3>

                    <p class="text-gray-600 whitespace-pre-line">
                        {{ $product->description }}
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection