<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display customer's orders.
     */
    public function index(): View
    {
        $orders = Order::where(
                'user_id',
                auth()->id()
            )
            ->latest()
            ->paginate(10);

        return view(
            'orders.index',
            compact('orders')
        );
    }

    /**
     * Display one customer's order.
     */
    public function show(Order $order): View
    {
        /*
         * Prevent customers from viewing
         * another customer's order.
         */
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'items.product',
        ]);

        return view(
            'orders.show',
            compact('order')
        );
    }
}