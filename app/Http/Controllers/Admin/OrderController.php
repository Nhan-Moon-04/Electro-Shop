<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'staff', 'payingMethod'])
            ->orderBy('order_date', 'desc');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Lọc theo ngày
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        // Tìm kiếm theo mã đơn hoặc tên khách
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('order_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', function($q) use ($search) {
                      $q->where('user_name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with([
            'customer',
            'staff',
            'payingMethod',
            'orderDetails.productVariant.product'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hoàn thành,Chờ thanh toán,Đang giao hàng,Đã hủy'
        ]);

        $order = Order::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $oldStatus = $order->order_status;
            $order->order_status = $request->status;
            
            // Cập nhật nhân viên xử lý
            if (!$order->staff_id) {
                $order->staff_id = 1; // Default staff ID
            }

            // Tự động cập nhật đã thanh toán khi hoàn thành
            if ($request->status == 'Hoàn thành') {
                $order->order_is_paid = 1;
                $order->order_paying_date = now();
            }

            $order->save();

            // Nếu hủy đơn, hoàn lại số lượng sản phẩm
            if ($request->status == 'Đã hủy' && $oldStatus != 'Đã hủy') {
                foreach ($order->orderDetails as $detail) {
                    // Logic hoàn lại kho (nếu cần)
                    // $detail->productVariant->increment('stock_quantity', $detail->order_detail_quantity);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Cập nhật địa chỉ giao hàng
    public function updateDeliveryAddress(Request $request, $id)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500'
        ]);

        $order = Order::findOrFail($id);
        $order->order_delivery_address = $request->delivery_address;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật địa chỉ giao hàng thành công!');
    }

    // Cập nhật ghi chú
    public function updateNote(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->order_note = $request->note;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật ghi chú thành công!');
    }

    // Xóa đơn hàng (soft delete hoặc hard delete)
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            // Chỉ cho phép xóa đơn hàng đã hủy
            if ($order->order_status != 'Đã hủy') {
                return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy!');
            }

            $order->delete();

            return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // In hóa đơn
    public function print($id)
    {
        $order = Order::with([
            'customer',
            'orderDetails.productVariant.product'
        ])->findOrFail($id);

        return view('admin.orders.print', compact('order'));
    }

    // Thống kê đơn hàng
    public function statistics(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $stats = [
            'total_orders' => Order::whereBetween('order_date', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('order_date', [$startDate, $endDate])
                ->where('order_status', 'Hoàn thành')
                ->sum('order_total_after'),
            'pending_orders' => Order::where('order_status', 'Chờ thanh toán')->count(),
            'shipping_orders' => Order::where('order_status', 'Đang giao hàng')->count(),
            'completed_orders' => Order::whereBetween('order_date', [$startDate, $endDate])
                ->where('order_status', 'Hoàn thành')->count(),
            'cancelled_orders' => Order::whereBetween('order_date', [$startDate, $endDate])
                ->where('order_status', 'Đã hủy')->count(),
        ];

        return view('admin.orders.statistics', compact('stats', 'startDate', 'endDate'));
    }
}