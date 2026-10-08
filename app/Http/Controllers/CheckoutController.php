<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with([
                'items.product.brand',
                'items.product.category',
            ])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return view('checkout.index', [
                'cart' => null,
                'subtotal' => 0,
                'shippingFee' => 0,
                'total' => 0,
            ]);
        }

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shippingFee = 0;

        $total = $subtotal + $shippingFee;

        return view('checkout.index', compact(
            'cart',
            'subtotal',
            'shippingFee',
            'total'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => [
                'required',
                'string',
                'max:1000',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'payment_method' => [
                'required',
                'in:cash_on_delivery',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $order = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Get the user's cart
            |--------------------------------------------------------------------------
            */

            $cart = Cart::where(
                'user_id',
                auth()->id()
            )
                ->with('items.product')
                ->lockForUpdate()
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                abort(
                    422,
                    'Your shopping cart is empty.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Lock products and check stock
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            $items = [];

            foreach ($cart->items as $cartItem) {

                $product = $cartItem->product;

                /*
                |--------------------------------------------------------------------------
                | Lock this product row
                |--------------------------------------------------------------------------
                */

                $product = $product
                    ->newQuery()
                    ->where('id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    abort(
                        422,
                        'One of the products is no longer available.'
                    );
                }

                if (!$product->is_active) {
                    abort(
                        422,
                        "{$product->name} is no longer available."
                    );
                }

                if ($product->stock <= 0) {
                    abort(
                        422,
                        "{$product->name} is out of stock."
                    );
                }

                if ($cartItem->quantity > $product->stock) {
                    abort(
                        422,
                        "Only {$product->stock} units of {$product->name} are available."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate item subtotal
                |--------------------------------------------------------------------------
                */

                $itemSubtotal =
                    $product->price * $cartItem->quantity;

                $subtotal += $itemSubtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Shipping
            |--------------------------------------------------------------------------
            */

            $shippingFee = 0;

            $total = $subtotal + $shippingFee;

            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'user_id' => auth()->id(),

                'order_number' =>
                    'ORD-' .
                    now()->format('YmdHis') .
                    '-' .
                    strtoupper(Str::random(5)),

                'subtotal' => $subtotal,

                'shipping_fee' => $shippingFee,

                'total_amount' => $total,

                'status' => 'pending',

                'payment_method' =>
                    $validated['payment_method'],

                'payment_status' => 'pending',

                'shipping_address' =>
                    $validated['shipping_address'],

                'phone' =>
                    $validated['phone'],

                'notes' =>
                    $validated['notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Order Items + Reduce Stock
            |--------------------------------------------------------------------------
            */

            foreach ($items as $item) {

                $product = $item['product'];

                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    'product_name' => $product->name,

                    'price' => $item['price'],

                    'quantity' => $item['quantity'],

                    'subtotal' => $item['subtotal'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Reduce stock
                |--------------------------------------------------------------------------
                */

                $product->decrement(
                    'stock',
                    $item['quantity']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with(
                'success',
                'Your order has been placed successfully.'
            );
    }
}