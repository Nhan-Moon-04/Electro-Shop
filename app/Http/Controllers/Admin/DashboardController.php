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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Thống kê tổng quan
        $totalProducts = Product::where('product_is_display', 1)->count();
        $totalOrders = Order::count();
        $totalUsers = Customer::count();
        
        // Tổng doanh thu (chỉ tính đơn hàng đã thanh toán)
        $totalRevenue = Order::where('order_is_paid', 1)
                             ->sum('order_total_after');
        
        // Doanh thu tháng hiện tại
        $currentMonthRevenue = Order::where('order_is_paid', 1)
                                    ->whereYear('order_date', date('Y'))
                                    ->whereMonth('order_date', date('m'))
                                    ->sum('order_total_after');
        
        // Doanh thu tháng trước
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthRevenue = Order::where('order_is_paid', 1)
                                 ->whereYear('order_date', $lastMonth->year)
                                 ->whereMonth('order_date', $lastMonth->month)
                                 ->sum('order_total_after');
        
        // Tính phần trăm tăng/giảm
        $revenuePercentChange = 0;
        if ($lastMonthRevenue > 0) {
            $revenuePercentChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }
        
        // Doanh thu theo 12 tháng (cho biểu đồ)
        $monthlyRevenue = Order::where('order_is_paid', 1)
            ->whereYear('order_date', $year)
            ->select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(order_total_after) as total'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy(DB::raw('MONTH(order_date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Tạo mảng đầy đủ 12 tháng
        $monthlyRevenueData = [];
        $monthlyOrdersData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueData[] = $monthlyRevenue->get($i)->total ?? 0;
            $monthlyOrdersData[] = $monthlyRevenue->get($i)->order_count ?? 0;
        }
        
        // Top 5 sản phẩm bán chạy nhất - FIX LỖI SQL
        $topProducts = DB::table('products')
            ->join('product_variants', 'products.product_id', '=', 'product_variants.product_id')
            ->join('order_details', 'product_variants.product_variant_id', '=', 'order_details.product_variant_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->where('orders.order_is_paid', 1)
            ->where('products.product_is_display', 1)
            ->select(
                'products.product_id',
                'products.product_name',
                'products.product_avt_img',
                'products.product_view_count',
                'categories.category_name',
                DB::raw('SUM(order_details.order_detail_quantity) as total_sold'),
                DB::raw('MIN(product_variants.product_variant_price) as min_price')
            )
            ->groupBy(
                'products.product_id',
                'products.product_name',
                'products.product_avt_img',
                'products.product_view_count',
                'categories.category_name'
            )
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 10 đơn hàng mới nhất
        $recentOrders = Order::orderBy('order_date', 'desc')
            ->take(10)
            ->get();

        // Thống kê sản phẩm theo danh mục
        $categoryStats = Category::select(
                'categories.category_id',
                'categories.category_name',
                DB::raw('COUNT(products.product_id) as products_count')
            )
            ->leftJoin('products', function($join) {
                $join->on('categories.category_id', '=', 'products.category_id')
                     ->where('products.product_is_display', '=', 1);
            })
            ->where('categories.category_is_display', 1)
            ->groupBy('categories.category_id', 'categories.category_name')
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->get();
        
        // Tính phần trăm cho từng danh mục
        $totalCategoryProducts = $categoryStats->sum('products_count');
        $categoryStats = $categoryStats->map(function($category) use ($totalCategoryProducts) {
            $category->percentage = $totalCategoryProducts > 0 
                ? round(($category->products_count / $totalCategoryProducts) * 100, 1) 
                : 0;
            return $category;
        });

        // Đơn hàng theo trạng thái
        $ordersByStatus = Order::select('order_status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'currentMonthRevenue',
            'revenuePercentChange',
            'monthlyRevenueData',
            'monthlyOrdersData',
            'year',
            'topProducts',
            'recentOrders',
            'categoryStats',
            'ordersByStatus'
        ));
    }
    
    public function getRevenueData(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $monthlyRevenue = Order::where('order_is_paid', 1)
            ->whereYear('order_date', $year)
            ->select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(order_total_after) as total'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy(DB::raw('MONTH(order_date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        $monthlyRevenueData = [];
        $monthlyOrdersData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueData[] = $monthlyRevenue->get($i)->total ?? 0;
            $monthlyOrdersData[] = $monthlyRevenue->get($i)->order_count ?? 0;
        }
        
        return response()->json([
            'revenue' => $monthlyRevenueData,
            'orders' => $monthlyOrdersData
        ]);
    }
}
