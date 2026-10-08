<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index(Request $request): View
    {
        $query = Brand::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        $brands = $query
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalBrands = Brand::count();

        $brandsWithProducts = Brand::has('products')
            ->count();

        $emptyBrands = Brand::doesntHave('products')
            ->count();

        return view(
            'admin.brands.index',
            compact(
                'brands',
                'totalBrands',
                'brandsWithProducts',
                'emptyBrands'
            )
        );
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create(): View
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request
                ->file('logo')
                ->store('brands', 'public');
        }

        Brand::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
        ]);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand created successfully.'
            );
    }

    /**
     * Display the specified brand.
     */
    public function show(Brand $brand): View
    {
        $brand->load([
            'products' => function ($query) {
                $query->latest();
            },
        ]);

        return view(
            'admin.brands.show',
            compact('brand')
        );
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand): View
    {
        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }

    /**
     * Update the specified brand.
     */
    public function update(
        Request $request,
        Brand $brand
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name,' . $brand->id,
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('logo')) {
            if (
                $brand->logo &&
                Storage::disk('public')
                    ->exists($brand->logo)
            ) {
                Storage::disk('public')
                    ->delete($brand->logo);
            }

            $brand->logo = $request
                ->file('logo')
                ->store('brands', 'public');
        }

        $brand->name = $validated['name'];

        $brand->description =
            $validated['description'] ?? null;

        $brand->save();

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand updated successfully.'
            );
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(
        Brand $brand
    ): RedirectResponse {
        if ($brand->products()->exists()) {
            return back()->with(
                'error',
                'This brand cannot be deleted because it contains products. Please move or delete the products first.'
            );
        }

        if (
            $brand->logo &&
            Storage::disk('public')
                ->exists($brand->logo)
        ) {
            Storage::disk('public')
                ->delete($brand->logo);
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand deleted successfully.'
            );
    }
}