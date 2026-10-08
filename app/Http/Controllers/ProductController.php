<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Product Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = Product::with([
            'category',
            'brand',
        ])->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where('category_id', $request->category);
        }

        /*
        |--------------------------------------------------------------------------
        | Brand Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('brand')) {

            $query->where('brand_id', $request->brand);
        }

        /*
        |--------------------------------------------------------------------------
        | Price Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->get('sort') === 'price_low') {

            $query->orderBy('price', 'asc');

        } elseif ($request->get('sort') === 'price_high') {

            $query->orderBy('price', 'desc');

        } elseif ($request->get('sort') === 'name') {

            $query->orderBy('name', 'asc');

        } else {

            $query->latest();
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'categories',
            'brands'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Product Details
    |--------------------------------------------------------------------------
    */

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'category',
            'brand',
        ]);

        $relatedProducts = Product::with([
            'category',
            'brand',
        ])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {

                $query->where(
                    'category_id',
                    $product->category_id
                )
                ->orWhere(
                    'brand_id',
                    $product->brand_id
                );

            })
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact(
            'product',
            'relatedProducts'
        ));
    }
}