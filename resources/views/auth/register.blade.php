@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    Create Account
                </h1>

                <p class="text-gray-500 mt-2">
                    Register for Phone E-commerce
                </p>

            </div>

            {{-- Errors --}}

            @if($errors->any())

                <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3">

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('register.submit') }}"
                class="space-y-5"
            >

                @csrf

                {{-- Name --}}

                <div>

                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                        placeholder="Enter your name"
                    >

                </div>

                {{-- Email --}}

                <div>

                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                        placeholder="Enter your email"
                    >

                </div>

                {{-- Password --}}

                <div>

                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                        placeholder="Minimum 8 characters"
                    >

                </div>

                {{-- Confirm Password --}}

                <div>

                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                        placeholder="Confirm your password"
                    >

                </div>

                {{-- Register Button --}}

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
                >
                    Create Account
                </button>

            </form>

            <div class="text-center mt-6">

                <p class="text-gray-600">

                    Already have an account?

                    <a
                        href="{{ route('login') }}"
                        class="text-blue-600 hover:text-blue-700 font-semibold"
                    >
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

@endsection