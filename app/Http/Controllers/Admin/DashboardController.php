<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $totalProducts = \App\Models\Product::count();
        $totalOrders = \App\Models\Order::count();
        $totalUsers = \App\Models\User::role('customer')->count();
        $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total_amount');

        // Recent orders
        $recentOrders = \App\Models\Order::with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        // Top selling products
        $topProducts = \App\Models\Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        // Monthly sales data for chart
        $monthlySales = \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Low stock products alert
        $lowStockProducts = \App\Models\Product::where('stock_status', 'low_stock')
            ->orWhere('stock_status', 'out_of_stock')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'topProducts',
            'monthlySales',
            'lowStockProducts'
        ));
    }
}
