@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $category->name }}
            </h1>

            <p class="text-gray-500 mt-1">
                Category details and products.
            </p>
        </div>

        <a
            href="{{ route('admin.categories.edit', $category) }}"
            class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            Edit Category
        </a>

    </div>


    <div class="bg-white rounded-xl shadow-sm border p-6">

        <h2 class="text-lg font-semibold text-gray-900">
            Description
        </h2>

        <p class="text-gray-600 mt-2">
            {{ $category->description ?: 'No description available.' }}
        </p>

    </div>


    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="px-6 py-4 border-b">

            <h2 class="text-lg font-semibold text-gray-900">
                Products in this Category
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

                    @forelse($category->products as $product)

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
                                No products in this category.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <a
        href="{{ route('admin.categories.index') }}"
        class="inline-block px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
    >
        ← Back to Categories
    </a>

</div>

@endsection