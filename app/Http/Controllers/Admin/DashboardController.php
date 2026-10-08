<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $totalBrands = Brand::count();

        $totalUsers = User::count();


        /*
        |--------------------------------------------------------------------------
        | Product Statistics
        |--------------------------------------------------------------------------
        */

        $activeProducts = Product::where(
            'is_active',
            true
        )->count();

        $lowStockProducts = Product::where(
            'stock',
            '<=',
            5
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Order Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::count();

        $pendingOrders = Order::where(
            'status',
            'pending'
        )->count();

        $processingOrders = Order::where(
            'status',
            'processing'
        )->count();

        $shippedOrders = Order::where(
            'status',
            'shipped'
        )->count();

        $deliveredOrders = Order::where(
            'status',
            'delivered'
        )->count();

        $cancelledOrders = Order::where(
            'status',
            'cancelled'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Orders
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $todayOrders = Order::whereDate(
            'created_at',
            $today
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Revenue
        |--------------------------------------------------------------------------
        |
        | We count delivered orders as completed revenue.
        |
        */

        $todayRevenue = Order::whereDate(
            'created_at',
            $today
        )
            ->where(
                'status',
                'delivered'
            )
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Total Revenue
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Order::where(
            'status',
            'delivered'
        )->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Products
        |--------------------------------------------------------------------------
        */

        $recentProducts = Product::with([
            'category',
            'brand',
        ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Low Stock Products
        |--------------------------------------------------------------------------
        */

        $lowStockProductsList = Product::with([
            'category',
            'brand',
        ])
            ->where(
                'stock',
                '<=',
                5
            )
            ->orderBy(
                'stock',
                'asc'
            )
            ->take(5)
            ->get();


        return view(
            'admin.dashboard.index',
            compact(
                'totalProducts',
                'totalCategories',
                'totalBrands',
                'totalUsers',
                'activeProducts',
                'lowStockProducts',
                'totalOrders',
                'pendingOrders',
                'processingOrders',
                'shippedOrders',
                'deliveredOrders',
                'cancelledOrders',
                'todayOrders',
                'todayRevenue',
                'totalRevenue',
                'recentOrders',
                'recentProducts',
                'lowStockProductsList'
            )
        );
    }
}