<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::query();

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

        $categories = $query
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalCategories = Category::count();

        $categoriesWithProducts = Category::has('products')
            ->count();

        $emptyCategories = Category::doesntHave('products')
            ->count();

        return view(
            'admin.categories.index',
            compact(
                'categories',
                'totalCategories',
                'categoriesWithProducts',
                'emptyCategories'
            )
        );
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): View
    {
        $category->load([
            'products' => function ($query) {
                $query->latest();
            },
        ]);

        return view(
            'admin.categories.show',
            compact('category')
        );
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    /**
     * Update the specified category.
     */
    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id,
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    /**
     * Remove the specified category.
     */
    public function destroy(
        Category $category
    ): RedirectResponse {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because it contains products. Please move or delete the products first.'
            );
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }
}