@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Edit User
        </h1>

        <p class="text-gray-500 mt-1">
            Update user information and role.
        </p>

    </div>


    @if(session('error'))

        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>

    @endif


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
        action="{{ route('admin.users.update', $user) }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border p-6 space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- Name --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Full Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
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
                value="{{ old('email', $user->email) }}"
                required
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
                    @selected(old('role', $user->role) === 'customer')
                >
                    Customer
                </option>

                <option
                    value="admin"
                    @selected(old('role', $user->role) === 'admin')
                >
                    Admin
                </option>

            </select>


            @if($user->id === auth()->id())

                <p class="text-xs text-orange-600 mt-2">
                    You cannot remove your own admin role.
                </p>

            @endif

        </div>


        {{-- Password --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                New Password
            </label>

            <input
                type="password"
                name="password"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

            <p class="text-xs text-gray-500 mt-1">
                Leave blank to keep the current password.
            </p>

        </div>


        {{-- Confirm Password --}}

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Confirm New Password
            </label>

            <input
                type="password"
                name="password_confirmation"
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
                Update User
            </button>

        </div>

    </form>

</div>

@endsection