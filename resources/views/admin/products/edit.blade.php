@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6">

        <h1 class="text-3xl font-bold text-gray-900">
            Edit Product
        </h1>

        <p class="text-gray-600 mt-1">
            Update {{ $product->name }}.
        </p>

    </div>


    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <form
            action="{{ route('admin.products.update', $product) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            @include(
                'admin.products._form'
            )


            <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg"
                >
                    💾 Update Product
                </button>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection