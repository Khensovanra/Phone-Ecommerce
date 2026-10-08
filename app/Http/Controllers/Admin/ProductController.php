<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display all products.
     */
    public function index(Request $request): View
    {
        $query = Product::with([
            'category',
            'brand',
        ]);

        /*
         * Search.
         */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'sku',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
         * Category filter.
         */
        if ($request->filled('category')) {
            $query->where(
                'category_id',
                $request->category
            );
        }

        /*
         * Brand filter.
         */
        if ($request->filled('brand')) {
            $query->where(
                'brand_id',
                $request->brand
            );
        }

        /*
         * Status filter.
         */
        if ($request->filled('status')) {

            if ($request->status === 'active') {
                $query->where(
                    'is_active',
                    true
                );
            }

            if ($request->status === 'inactive') {
                $query->where(
                    'is_active',
                    false
                );
            }
        }

        /*
         * Sorting.
         */
        if ($request->get('sort') === 'price_low') {

            $query->orderBy(
                'price',
                'asc'
            );

        } elseif ($request->get('sort') === 'price_high') {

            $query->orderBy(
                'price',
                'desc'
            );

        } elseif ($request->get('sort') === 'stock_low') {

            $query->orderBy(
                'stock',
                'asc'
            );

        } elseif ($request->get('sort') === 'stock_high') {

            $query->orderBy(
                'stock',
                'desc'
            );

        } elseif ($request->get('sort') === 'name') {

            $query->orderBy(
                'name',
                'asc'
            );

        } else {

            $query->latest();
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        /*
         * Statistics.
         */
        $totalProducts = Product::count();

        $activeProducts = Product::where(
            'is_active',
            true
        )->count();

        $inactiveProducts = Product::where(
            'is_active',
            false
        )->count();

        $lowStockProducts = Product::where(
            'stock',
            '<=',
            5
        )->count();

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories',
                'brands',
                'totalProducts',
                'activeProducts',
                'inactiveProducts',
                'lowStockProducts'
            )
        );
    }

    /**
     * Show create product form.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view(
            'admin.products.create',
            compact(
                'categories',
                'brands'
            )
        );
    }

    /**
     * Store a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'required',
                'exists:brands,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Generate slug automatically
         * if admin leaves it empty.
         */
        $slug = $validated['slug']
            ?? Str::slug($validated['name']);

        /*
         * Make slug unique.
         */
        $originalSlug = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)->exists()
        ) {
            $slug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }

        /*
         * Upload image.
         */
        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );
        }

        /*
         * Create product.
         */
        Product::create([
            'category_id' =>
                $validated['category_id'],

            'brand_id' =>
                $validated['brand_id'],

            'name' =>
                $validated['name'],

            'slug' =>
                $slug,

            'description' =>
                $validated['description'] ?? null,

            'price' =>
                $validated['price'],

            'stock' =>
                $validated['stock'],

            'image' =>
                $imagePath,

            'sku' =>
                $validated['sku'],

            'is_active' =>
                $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    /**
     * Display one product.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'brand',
        ]);

        return view(
            'admin.products.show',
            compact('product')
        );
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'brands'
            )
        );
    }

    /**
     * Update product.
     */
    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {

        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'required',
                'exists:brands,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,slug,' . $product->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku,' . $product->id,
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Keep existing slug if empty.
         */
        $slug = $validated['slug']
            ?: $product->slug;

        /*
         * Upload new image.
         */
        if ($request->hasFile('image')) {

            /*
             * Delete old image.
             */
            if (
                $product->image &&
                Storage::disk('public')
                    ->exists($product->image)
            ) {

                Storage::disk('public')
                    ->delete($product->image);
            }

            /*
             * Store new image.
             */
            $product->image =
                $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );
        }

        /*
         * Update product.
         */
        $product->update([
            'category_id' =>
                $validated['category_id'],

            'brand_id' =>
                $validated['brand_id'],

            'name' =>
                $validated['name'],

            'slug' =>
                $slug,

            'description' =>
                $validated['description'] ?? null,

            'price' =>
                $validated['price'],

            'stock' =>
                $validated['stock'],

            'sku' =>
                $validated['sku'],

            'is_active' =>
                $request->boolean('is_active'),
        ]);

        /*
         * Save image separately because
         * it may have been changed.
         */
        if ($product->image) {
            $product->save();
        }

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    /**
     * Delete product.
     */
    public function destroy(
        Product $product
    ): RedirectResponse {

        /*
         * Prevent deleting a product that
         * already exists in order history.
         */
        if ($product->orderItems()->exists()) {

            return back()->with(
                'error',
                'This product cannot be deleted because it exists in order history. Deactivate it instead.'
            );
        }

        /*
         * Delete product image.
         */
        if (
            $product->image &&
            Storage::disk('public')
                ->exists($product->image)
        ) {

            Storage::disk('public')
                ->delete($product->image);
        }

        /*
         * Delete product.
         */
        $product->delete();

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }
}