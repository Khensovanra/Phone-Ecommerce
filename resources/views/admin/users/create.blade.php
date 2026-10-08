@extends('admin.layouts.app')

@section('title', 'Add User')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Add User
        </h1>

        <p class="text-gray-500 mt-1">
            Create a customer or administrator account.
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
        action="{{ route('admin.users.store') }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border p-6 space-y-6"
    >

        @csrf


        {{-- Name --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Full Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="John Doe"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- Email --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="john@example.com"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        {{-- Role --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Role
            </label>

            <select
                name="role"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option
                    value="customer"
                    @selected(old('role', 'customer') === 'customer')
                >
                    Customer
                </option>

                <option
                    value="admin"
                    @selected(old('role') === 'admin')
                >
                    Admin
                </option>

            </select>

        </div>


        {{-- Password --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

            <p class="text-xs text-gray-500 mt-1">
                Minimum 8 characters.
            </p>

        </div>


        {{-- Confirm Password --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

        </div>


        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.users.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Create User
            </button>

        </div>

    </form>

</div>

@endsection