<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->get('sort') === 'oldest') {
            $query->oldest();
        } elseif ($request->get('sort') === 'amount_high') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($request->get('sort') === 'amount_low') {
            $query->orderBy('total_amount', 'asc');
        } else {
            $query->latest();
        }

        $orders = $query
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $processingOrders = Order::where('status', 'processing')->count();

        $shippedOrders = Order::where('status', 'shipped')->count();

        $deliveredOrders = Order::where('status', 'delivered')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Added to satisfy $completedOrders in index.blade.php
        $completedOrders = $deliveredOrders;

        return view(
            'admin.orders.index',
            compact(
                'orders',
                'totalOrders',
                'pendingOrders',
                'processingOrders',
                'shippedOrders',
                'deliveredOrders',
                'completedOrders',
                'cancelledOrders'
            )
        );
    }

    public function show(Order $order): View
    {
        $order->load([
            'user',
            'items.product',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function update(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,cancelled',
            ],
        ]);

        $newStatus = $validated['status'];

        /*
        |--------------------------------------------------------------------------
        | Do not do anything if status is unchanged
        |--------------------------------------------------------------------------
        */

        if ($order->status === $newStatus) {
            return back()->with(
                'success',
                'Order status is already ' . $newStatus . '.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancellation
        |--------------------------------------------------------------------------
        */

        if ($newStatus === 'cancelled') {
            DB::transaction(function () use ($order) {
                $lockedOrder = Order::where('id', $order->id)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedOrder) {
                    abort(404, 'Order not found.');
                }

                if ($lockedOrder->status === 'cancelled') {
                    abort(422, 'This order has already been cancelled.');
                }

                if (in_array($lockedOrder->status, ['shipped', 'delivered'], true)) {
                    abort(422, 'Shipped or delivered orders cannot be cancelled.');
                }

                $lockedOrder->load('items');

                foreach ($lockedOrder->items as $item) {
                    $product = $item->product;

                    if (!$product) {
                        continue;
                    }

                    $product = $product
                        ->newQuery()
                        ->where('id', $product->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$product) {
                        continue;
                    }

                    $product->increment('stock', $item->quantity);
                }

                $lockedOrder->update([
                    'status' => 'cancelled',
                    'payment_status' => 'cancelled',
                ]);
            });

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order cancelled and product stock restored successfully.');
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Status Update
        |--------------------------------------------------------------------------
        */

        $allowedTransitions = [
            'pending' => ['processing'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];

        $currentStatus = $order->status;

        if (
            !isset($allowedTransitions[$currentStatus]) ||
            !in_array($newStatus, $allowedTransitions[$currentStatus], true)
        ) {
            return back()->with(
                'error',
                "Cannot change order status from {$currentStatus} to {$newStatus}."
            );
        }

        $order->update([
            'status' => $newStatus,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($newStatus === 'delivered') {
            $order->update([
                'payment_status' => 'paid',
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}