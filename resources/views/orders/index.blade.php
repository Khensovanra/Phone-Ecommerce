@extends('layouts.app')

@section('title', 'My Orders')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900">
            My Orders
        </h1>

        <p class="mt-2 text-gray-600">
            View your order history and order details.
        </p>

    </div>


    @if(session('success'))

        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    @if($orders->count())

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Order
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Date
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Total
                            </th>

                            <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="text-right px-6 py-4 text-sm font-semibold text-gray-700">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @foreach($orders as $order)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <div class="font-semibold text-gray-900">
                                        {{ $order->order_number }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $order->items()->count() }}
                                        item(s)
                                    </div>

                                </td>


                                <td class="px-6 py-4 text-gray-600">

                                    {{ $order->created_at->format('M d, Y') }}

                                </td>


                                <td class="px-6 py-4 font-semibold text-gray-900">

                                    ${{ number_format($order->total_amount, 2) }}

                                </td>


                                <td class="px-6 py-4">

                                    @if($order->status === 'pending')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>

                                    @elseif($order->status === 'processing')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            Processing
                                        </span>

                                    @elseif($order->status === 'shipped')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Shipped
                                        </span>

                                    @elseif($order->status === 'delivered')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Delivered
                                        </span>

                                    @elseif($order->status === 'cancelled')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-800 font-semibold"
                                    >
                                        View →
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        <div class="mt-6">

            {{ $orders->links() }}

        </div>

    @else

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">

            <div class="text-6xl mb-4">
                📦
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                No orders yet
            </h2>

            <p class="text-gray-600 mt-2">
                You haven't placed any orders yet.
            </p>

            <a
                href="{{ route('products.index') }}"
                class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg"
            >
                Start Shopping
            </a>

        </div>

    @endif

</div>

@endsection