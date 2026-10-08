@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Add Category
        </h1>

        <p class="text-gray-500 mt-1">
            Create a new product category.
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
        action="{{ route('admin.categories.store') }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border p-6 space-y-6"
    >

        @csrf


        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Category Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Example: Flagship"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>

            <textarea
                name="description"
                rows="5"
                placeholder="Enter category description..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >{{ old('description') }}</textarea>

        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.categories.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Create Category
            </button>

        </div>

    </form>

</div>

@endsection