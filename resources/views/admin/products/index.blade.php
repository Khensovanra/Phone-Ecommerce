@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Products
            </h1>

            <p class="text-gray-600 mt-1">
                Manage your phone products.
            </p>

        </div>


        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg"
        >
            ➕ Add Product
        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))

        <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Total Products
            </p>

            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $totalProducts }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Active Products
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $activeProducts }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Inactive Products
            </p>

            <p class="text-3xl font-bold text-gray-500 mt-2">
                {{ $inactiveProducts }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Low Stock
            </p>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $lowStockProducts }}
            </p>

        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

        <form
            action="{{ route('admin.products.index') }}"
            method="GET"
            class="grid grid-cols-1 md:grid-cols-6 gap-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Product name, SKU..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                >

            </div>


            {{-- Category --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Category
                </label>

                <select
                    name="category"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                >

                    <option value="">
                        All
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
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                >

                    <option value="">
                        All
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


            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Sort --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sort
                </label>

                <select
                    name="sort"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
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
                        Price: Low
                    </option>

                    <option
                        value="price_high"
                        @selected(request('sort') === 'price_high')
                    >
                        Price: High
                    </option>

                    <option
                        value="stock_low"
                        @selected(request('sort') === 'stock_low')
                    >
                        Stock: Low
                    </option>

                    <option
                        value="stock_high"
                        @selected(request('sort') === 'stock_high')
                    >
                        Stock: High
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="md:col-span-6 flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg"
                >
                    🔍 Filter
                </button>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-lg"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Products --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        @if($products->count())

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Product
                            </th>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Category
                            </th>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Brand
                            </th>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Price
                            </th>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Stock
                            </th>

                            <th class="text-left px-5 py-4 text-sm font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="text-right px-5 py-4 text-sm font-semibold text-gray-700">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @foreach($products as $product)

                            <tr class="hover:bg-gray-50">

                                {{-- Product --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-4">

                                        <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">

                                            @if($product->image)

                                                <img
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover"
                                                >

                                            @else

                                                <span class="text-2xl">
                                                    📱
                                                </span>

                                            @endif

                                        </div>


                                        <div>

                                            <div class="font-semibold text-gray-900">
                                                {{ $product->name }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                SKU: {{ $product->sku }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Category --}}
                                <td class="px-5 py-4 text-gray-600">

                                    {{ $product->category->name }}

                                </td>


                                {{-- Brand --}}
                                <td class="px-5 py-4 text-gray-600">

                                    {{ $product->brand->name }}

                                </td>


                                {{-- Price --}}
                                <td class="px-5 py-4 font-semibold text-gray-900">

                                    ${{ number_format($product->price, 2) }}

                                </td>


                                {{-- Stock --}}
                                <td class="px-5 py-4">

                                    @if($product->stock === 0)

                                        <span class="font-semibold text-red-600">
                                            Out of Stock
                                        </span>

                                    @elseif($product->stock <= 5)

                                        <span class="font-semibold text-orange-600">
                                            {{ $product->stock }} Low
                                        </span>

                                    @else

                                        <span class="font-semibold text-green-600">
                                            {{ $product->stock }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @if($product->is_active)

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Active
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('admin.products.show', $product) }}"
                                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.products.destroy', $product) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="p-5 border-t border-gray-200">

                {{ $products->links() }}

            </div>

        @else

            <div class="p-12 text-center">

                <div class="text-6xl mb-4">
                    📱
                </div>

                <h2 class="text-xl font-bold text-gray-900">
                    No products found
                </h2>

                <p class="text-gray-500 mt-2">
                    Try changing your search or filters.
                </p>

                <a
                    href="{{ route('admin.products.create') }}"
                    class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold"
                >
                    ➕ Add Product
                </a>

            </div>

        @endif

    </div>

</div>

@endsection