@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    Welcome Back
                </h1>

                <p class="text-gray-500 mt-2">
                    Login to your account
                </p>

            </div>

            {{-- Success Message --}}

            @if(session('success'))

                <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>

            @endif

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
                action="{{ route('login.submit') }}"
                class="space-y-5"
            >

                @csrf

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
                        autofocus
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
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                        placeholder="Enter your password"
                    >

                </div>

                {{-- Login Button --}}

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
                >
                    Login
                </button>

            </form>

            <div class="text-center mt-6">

                <p class="text-gray-600">

                    Don't have an account?

                    <a
                        href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-700 font-semibold"
                    >
                        Register
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

@endsection