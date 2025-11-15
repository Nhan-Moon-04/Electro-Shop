<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = Customer::count();
        $totalRevenue = Order::where('order_status', 'delivered')
                             ->sum('order_total_after');
        
        // Top 5 sản phẩm xem nhiều nhất
        $topProducts = Product::with('category')
            ->withCount(['variants as min_price' => function($query) {
                $query->select(DB::raw('MIN(product_variant_price)'));
            }])
            ->orderBy('product_view_count', 'desc')
            ->take(5)
            ->get();

        // 10 đơn hàng mới nhất
        $recentOrders = Order::with(['customer.user'])
            ->orderBy('order_date', 'desc')
            ->take(10)
            ->get();

        // Thống kê sản phẩm theo danh mục
        $categoryStats = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'topProducts',
            'recentOrders',
            'categoryStats'
        ));
    }
}
