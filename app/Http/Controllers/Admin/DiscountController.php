<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('discount_start_date', 'desc')->paginate(15);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_name' => 'required|string|max:255',
            'discount_description' => 'nullable|string',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date|after:discount_start_date',
            'discount_amount' => 'required|numeric|min:0|max:100',
            'discount_is_display' => 'boolean'
        ], [
            'discount_name.required' => 'Vui lòng nhập tên khuyến mãi',
            'discount_start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'discount_end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'discount_end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'discount_amount.required' => 'Vui lòng nhập phần trăm giảm giá',
            'discount_amount.max' => 'Phần trăm giảm giá không được vượt quá 100%',
            'discount_amount.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Discount::create([
            'discount_name' => $request->discount_name,
            'discount_description' => $request->discount_description,
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
            'discount_amount' => $request->discount_amount,
            'discount_is_display' => $request->has('discount_is_display') ? 1 : 0
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Tạo mã khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'discount_name' => 'required|string|max:255',
            'discount_description' => 'nullable|string',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date|after:discount_start_date',
            'discount_amount' => 'required|numeric|min:0|max:100',
            'discount_is_display' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $discount->update([
            'discount_name' => $request->discount_name,
            'discount_description' => $request->discount_description,
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
            'discount_amount' => $request->discount_amount,
            'discount_is_display' => $request->has('discount_is_display') ? 1 : 0
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Xóa khuyến mãi thành công!');
    }

    public function toggleDisplay($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->discount_is_display = !$discount->discount_is_display;
        $discount->save();

        return response()->json([
            'success' => true,
            'is_display' => $discount->discount_is_display,
            'message' => $discount->discount_is_display ? 'Đã bật hiển thị' : 'Đã tắt hiển thị'
        ]);
    }

    public function statistics($id)
    {
        $discount = Discount::with(['orders', 'orders.customer'])->findOrFail($id);
        
        // Thống kê chi tiết
        $orders = collect($discount->orders);
        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('order_total_after'),
            'total_discount_amount' => $orders->sum(function($order) {
                return $order->order_total_before - $order->order_total_after;
            }),
            'days_remaining' => now()->diffInDays($discount->discount_end_date, false),
            'is_active' => $discount->isActive()
        ];

        return view('admin.discounts.statistics', compact('discount', 'stats'));
    }
}