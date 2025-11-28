<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'orders']);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('user_email', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('user_phone', 'like', "%{$search}%");
            });
        }

        // Filter by user active status
        if ($request->has('status') && $request->status !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('user_active', $request->status);
            });
        }

        $customers = $query->orderBy('customer_id', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => Customer::count(),
            'active' => Customer::whereHas('user', function($q) {
                $q->where('user_active', 1);
            })->count(),
            'inactive' => Customer::whereHas('user', function($q) {
                $q->where('user_active', 0);
            })->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show($id)
    {
        $customer = Customer::with(['user', 'orders.orderDetails.productVariant.product'])
            ->findOrFail($id);

        // Customer statistics
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->sum('order_total_after'),
            'pending_orders' => $customer->orders()->where('order_status', 'Chờ thanh toán')->count(),
            'completed_orders' => $customer->orders()->where('order_status', 'Hoàn thành')->count(),
        ];

        // Recent orders
        $recentOrders = $customer->orders()
            ->orderBy('order_date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.customers.show', compact('customer', 'stats', 'recentOrders'));
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_phone' => 'nullable|string|max:20',
            'user_address' => 'nullable|string|max:500',
            'user_active' => 'boolean',
        ], [
            'user_name.required' => 'Tên khách hàng không được để trống',
        ]);

        try {
            if ($customer->user) {
                $customer->user->update([
                    'user_name' => $request->user_name,
                    'user_phone' => $request->user_phone,
                    'user_address' => $request->user_address,
                    'user_active' => $request->has('user_active') ? 1 : 0,
                ]);
            }

            return redirect()->route('admin.customers.index')
                ->with('success', 'Cập nhật thông tin khách hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Check if customer has orders
            $orderCount = $customer->orders()->count();

            if ($orderCount > 0) {
                return redirect()->route('admin.customers.index')
                    ->with('error', 'Không thể xóa khách hàng đã có đơn hàng. Vui lòng đổi trạng thái thành không hoạt động.');
            }

            // Delete associated user if exists
            if ($customer->user) {
                $customer->user->delete();
            }

            $customer->delete();

            return redirect()->route('admin.customers.index')
                ->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $customer = Customer::with('user')->findOrFail($id);
            
            if ($customer->user) {
                $customer->user->user_active = !$customer->user->user_active;
                $customer->user->save();

                return response()->json([
                    'success' => true,
                    'is_active' => $customer->user->user_active,
                    'message' => $customer->user->user_active ? 'Đã kích hoạt' : 'Đã vô hiệu hóa'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy user liên kết'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statistics()
    {
        // Overall statistics
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::join('users', 'customers.user_id', '=', 'users.user_id')
                ->where('users.user_active', 1)->count(),
            'inactive_customers' => Customer::join('users', 'customers.user_id', '=', 'users.user_id')
                ->where('users.user_active', 0)->count(),
            'new_this_month' => Customer::join('users', 'customers.user_id', '=', 'users.user_id')
                ->whereMonth('users.user_register_date', now()->month)
                ->whereYear('users.user_register_date', now()->year)
                ->count(),
        ];

        // Top customers by spending
        $topCustomers = Customer::with('user')
            ->select('customers.*', DB::raw('SUM(orders.order_total_after) as total_spent'))
            ->leftJoin('orders', 'customers.customer_id', '=', 'orders.customer_id')
            ->groupBy('customers.customer_id', 'customers.user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Customers by order count
        $customersByOrders = Customer::with('user')
            ->select('customers.*', DB::raw('COUNT(orders.order_id) as order_count'))
            ->leftJoin('orders', 'customers.customer_id', '=', 'orders.customer_id')
            ->groupBy('customers.customer_id', 'customers.user_id')
            ->orderBy('order_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.customers.statistics', compact('stats', 'topCustomers', 'customersByOrders'));
    }
}
