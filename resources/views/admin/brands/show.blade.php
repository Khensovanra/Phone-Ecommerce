@extends('admin.layouts.app')

@section('title', 'Brand Details')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $brand->name }}
            </h1>

            <p class="text-gray-500 mt-1">
                Brand details and products.
            </p>
        </div>

        <a
            href="{{ route('admin.brands.edit', $brand) }}"
            class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            Edit Brand
        </a>

    </div>


    {{-- Brand Information --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        <div class="flex flex-col md:flex-row gap-6">

            <div>

                @if($brand->logo)

                    <img
                        src="{{ asset('storage/' . $brand->logo) }}"
                        alt="{{ $brand->name }}"
                        class="w-40 h-40 object-contain border rounded-xl p-3"
                    >

                @else

                    <div class="w-40 h-40 bg-gray-100 rounded-xl flex items-center justify-center text-5xl">
                        🏷️
                    </div>

                @endif

            </div>


            <div class="flex-1">

                <h2 class="text-xl font-bold text-gray-900">
                    {{ $brand->name }}
                </h2>

                <p class="text-gray-600 mt-3">
                    {{ $brand->description ?: 'No description available.' }}
                </p>

                <div class="mt-4">

                    <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        {{ $brand->products->count() }} Products
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Products --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="px-6 py-4 border-b">

            <h2 class="text-lg font-semibold text-gray-900">
                Products by {{ $brand->name }}
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left">
                            Product
                        </th>

                        <th class="px-6 py-4 text-left">
                            SKU
                        </th>

                        <th class="px-6 py-4 text-left">
                            Price
                        </th>

                        <th class="px-6 py-4 text-left">
                            Stock
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($brand->products as $product)

                        <tr>

                            <td class="px-6 py-4 font-medium">
                                {{ $product->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->sku }}
                            </td>

                            <td class="px-6 py-4">
                                ${{ number_format($product->price, 2) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $product->stock }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No products for this brand.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <a
        href="{{ route('admin.brands.index') }}"
        class="inline-block px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
    >
        ← Back to Brands
    </a>

</div>

@endsection