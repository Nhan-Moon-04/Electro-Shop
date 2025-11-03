<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Dữ liệu mẫu để hiển thị giao diện admin (không cần database)
        $totalProducts = 0;
        $totalOrders = 0;
        $totalUsers = 0;
        $totalRevenue = 0;
        
        // Dữ liệu mẫu rỗng
        $topProducts = collect([]);
        $recentOrders = collect([]);
        $categoryStats = collect([]);

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
