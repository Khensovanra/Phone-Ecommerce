@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                {{ $user->name }}
            </h1>

            <p class="text-gray-500 mt-1">
                User account details.
            </p>

        </div>


        <div class="flex gap-3">

            <a
                href="{{ route('admin.users.edit', $user) }}"
                class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Edit User
            </a>

            <a
                href="{{ route('admin.users.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- User Information --}}

    <div class="bg-white rounded-xl shadow-sm border p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <p class="text-sm text-gray-500">
                    Name
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $user->name }}
                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Email
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $user->email }}
                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Role
                </p>

                <p class="mt-1">

                    @if($user->role === 'admin')

                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Admin
                        </span>

                    @else

                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Customer
                        </span>

                    @endif

                </p>

            </div>


            <div>

                <p class="text-sm text-gray-500">
                    Joined
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $user->created_at->format('M d, Y h:i A') }}
                </p>

            </div>

        </div>

    </div>


    {{-- Orders --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="px-6 py-4 border-b">

            <h2 class="text-lg font-semibold text-gray-900">
                Order History
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left">
                            Order
                        </th>

                        <th class="px-6 py-4 text-left">
                            Total
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left">
                            Date
                        </th>

                        <th class="px-6 py-4 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($user->orders as $order)

                        <tr>

                            <td class="px-6 py-4 font-medium">
                                {{ $order->order_number }}
                            </td>

                            <td class="px-6 py-4">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($order->status) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.orders.show', $order) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    View Order
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                This user has no orders yet.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection