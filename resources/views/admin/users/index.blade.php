@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')

<div class="space-y-6">

    {{-- Header --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Users
            </h1>

            <p class="text-gray-500 mt-1">
                Manage customers and administrators.
            </p>

        </div>


        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            + Add User
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
                Total Users
            </p>

            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $totalUsers }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Administrators
            </p>

            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalAdmins }}
            </p>

        </div>


        <div class="bg-white rounded-xl shadow-sm border p-5">

            <p class="text-sm text-gray-500">
                Customers
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $totalCustomers }}
            </p>

        </div>

    </div>


    {{-- Search / Filters --}}

    <div class="bg-white rounded-xl shadow-sm border p-5">

        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3"
        >

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name or email..."
                class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >


            <select
                name="role"
                class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    All Roles
                </option>

                <option
                    value="admin"
                    @selected(request('role') === 'admin')
                >
                    Admin
                </option>

                <option
                    value="customer"
                    @selected(request('role') === 'customer')
                >
                    Customer
                </option>

            </select>


            <select
                name="sort"
                class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >

                <option value="">
                    Newest
                </option>

                <option
                    value="oldest"
                    @selected(request('sort') === 'oldest')
                >
                    Oldest
                </option>

                <option
                    value="name"
                    @selected(request('sort') === 'name')
                >
                    Name A-Z
                </option>

                <option
                    value="admins"
                    @selected(request('sort') === 'admins')
                >
                    Admins First
                </option>

            </select>


            <div class="flex gap-2">

                <button
                    type="submit"
                    class="flex-1 px-5 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                >
                    Search
                </button>


                @if(request()->hasAny([
                    'search',
                    'role',
                    'sort'
                ]))

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                    >
                        Clear
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Users Table --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            #
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            User
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">
                            Role
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">
                            Orders
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            Joined
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-500">
                                {{ $users->firstItem() + $loop->index }}
                            </td>


                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-900">
                                    {{ $user->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </div>

                            </td>


                            <td class="px-6 py-4 text-center">

                                @if($user->role === 'admin')

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Admin
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Customer
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-center">

                                <span class="text-gray-700">
                                    {{ $user->orders()->count() }}
                                </span>

                            </td>


                            <td class="px-6 py-4">

                                <div class="text-sm text-gray-700">
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                    >
                                        Edit
                                    </a>


                                    @if($user->id !== auth()->id())

                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
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

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-5xl mb-3">
                                    👥
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900">
                                    No users found
                                </h3>

                                <p class="text-gray-500 mt-1">
                                    Users will appear here.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($users->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>

@endsection