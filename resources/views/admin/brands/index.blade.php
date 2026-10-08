@extends('admin.layouts.app')

@section('title', 'Brands')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Brands
            </h1>

            <p class="text-gray-500 mt-1">
                Manage phone brands.
            </p>
        </div>

        <a
            href="{{ route('admin.brands.create') }}"
            class="inline-flex items-center justify-center px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            + Add Brand
        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Total Brands
            </p>

            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $totalBrands }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Brands With Products
            </p>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $brandsWithProducts }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Empty Brands
            </p>

            <p class="text-3xl font-bold text-orange-500 mt-2">
                {{ $emptyBrands }}
            </p>

        </div>

    </div>


    {{-- Search --}}
    <div class="bg-white rounded-xl shadow-sm border p-5">

        <form
            method="GET"
            action="{{ route('admin.brands.index') }}"
            class="flex flex-col md:flex-row gap-3"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search brand..."
                class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

            <button
                type="submit"
                class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
            >
                Search
            </button>

            @if(request('search'))

                <a
                    href="{{ route('admin.brands.index') }}"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- Brand Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            #
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            Brand
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            Description
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">
                            Products
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($brands as $brand)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-500">
                                {{ $brands->firstItem() + $loop->index }}
                            </td>


                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    @if($brand->logo)

                                        <img
                                            src="{{ asset('storage/' . $brand->logo) }}"
                                            alt="{{ $brand->name }}"
                                            class="w-12 h-12 object-contain rounded-lg border bg-white p-1"
                                        >

                                    @else

                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-xl">
                                            🏷️
                                        </div>

                                    @endif


                                    <div class="font-semibold text-gray-900">
                                        {{ $brand->name }}
                                    </div>

                                </div>

                            </td>


                            <td class="px-6 py-4 text-gray-600">

                                {{ $brand->description
                                    ? Str::limit($brand->description, 60)
                                    : 'No description'
                                }}

                            </td>


                            <td class="px-6 py-4 text-center">

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                    {{ $brand->products_count > 0
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-gray-100 text-gray-600'
                                    }}"
                                >
                                    {{ $brand->products_count }}
                                </span>

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.brands.show', $brand) }}"
                                        class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('admin.brands.edit', $brand) }}"
                                        class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.brands.destroy', $brand) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this brand?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-5xl mb-3">
                                    🏷️
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900">
                                    No brands found
                                </h3>

                                <p class="text-gray-500 mt-1">
                                    Create your first brand.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($brands->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $brands->links() }}
            </div>

        @endif

    </div>

</div>

@endsection