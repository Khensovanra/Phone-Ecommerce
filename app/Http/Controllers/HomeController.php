<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::with([
            'category',
            'brand',
        ])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::orderBy('name')
            ->take(6)
            ->get();

        $brands = Brand::orderBy('name')
            ->take(6)
            ->get();

        return view('home.index', compact(
            'featuredProducts',
            'categories',
            'brands'
        ));
    }
}