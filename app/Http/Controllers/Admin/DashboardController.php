<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $data = [
            'totalSales' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('order_status', 'pending')->count(),
            'totalCustomers' => Customer::count(),
            'totalProducts' => Product::count(),
            'lowStockProducts' => Product::where('quantity', '<=', 5)->count(),
            'activeCategories' => Category::active()->count(),
            'recentOrders' => Order::with('customer')->latest()->limit(8)->get(),
            'topProducts' => Product::withCount('orderItems')
                ->orderByDesc('order_items_count')
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('data'));
    }
}
