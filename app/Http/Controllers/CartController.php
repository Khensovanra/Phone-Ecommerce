<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Cart
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load([
            'items.product.brand',
            'items.product.category',
        ]);

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact(
            'cart',
            'subtotal'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Add Product To Cart
    |--------------------------------------------------------------------------
    */

    public function add(
        Request $request,
        Product $product
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Check Product
        |--------------------------------------------------------------------------
        */

        if (!$product->is_active) {

            return back()->with(
                'error',
                'This product is not available.'
            );
        }

        if ($product->stock <= 0) {

            return back()->with(
                'error',
                'This product is out of stock.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Quantity
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $quantity = $validated['quantity'] ?? 1;


        /*
        |--------------------------------------------------------------------------
        | Get / Create Cart
        |--------------------------------------------------------------------------
        */

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check Existing Item
        |--------------------------------------------------------------------------
        */

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();


        if ($cartItem) {

            $newQuantity = $cartItem->quantity + $quantity;

            /*
            |--------------------------------------------------------------------------
            | Stock Check
            |--------------------------------------------------------------------------
            */

            if ($newQuantity > $product->stock) {

                return back()->with(
                    'error',
                    "Only {$product->stock} units are available."
                );
            }

            $cartItem->update([
                'quantity' => $newQuantity,
            ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Check Stock
            |--------------------------------------------------------------------------
            */

            if ($quantity > $product->stock) {

                return back()->with(
                    'error',
                    "Only {$product->stock} units are available."
                );
            }

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }


        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                $product->name . ' added to your cart.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Cart Item
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if ($cartItem->cart->user_id !== auth()->id()) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);


        $quantity = $validated['quantity'];

        $product = $cartItem->product;


        /*
        |--------------------------------------------------------------------------
        | Stock Check
        |--------------------------------------------------------------------------
        */

        if ($quantity > $product->stock) {

            return back()->with(
                'error',
                "Only {$product->stock} units are available."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $cartItem->update([
            'quantity' => $quantity,
        ]);


        return back()->with(
            'success',
            'Cart updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Cart Item
    |--------------------------------------------------------------------------
    */

    public function remove(
        CartItem $cartItem
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if ($cartItem->cart->user_id !== auth()->id()) {

            abort(403);
        }


        $cartItem->delete();


        return back()->with(
            'success',
            'Product removed from cart.'
        );
    }
}