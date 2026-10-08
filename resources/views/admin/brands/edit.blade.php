@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Edit Brand
        </h1>

        <p class="text-gray-500 mt-1">
            Update brand information.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">

            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.brands.update', $brand) }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm border p-6 space-y-6"
    >

        @csrf
        @method('PUT')


        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Brand Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $brand->name) }}"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        @if($brand->logo)

            <div>

                <p class="text-sm font-medium text-gray-700 mb-2">
                    Current Logo
                </p>

                <img
                    src="{{ asset('storage/' . $brand->logo) }}"
                    alt="{{ $brand->name }}"
                    class="w-32 h-32 object-contain border rounded-lg p-2"
                >

            </div>

        @endif


        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Replace Logo
            </label>

            <input
                type="file"
                name="logo"
                accept="image/jpeg,image/png,image/webp"
                class="w-full rounded-lg border border-gray-300 p-2"
            >

            <p class="text-xs text-gray-500 mt-2">
                Leave empty to keep the current logo.
            </p>

        </div>


        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>

            <textarea
                name="description"
                rows="5"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('description', $brand->description) }}</textarea>

        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.brands.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Update Brand
            </button>

        </div>

    </form>

</div>

@endsection