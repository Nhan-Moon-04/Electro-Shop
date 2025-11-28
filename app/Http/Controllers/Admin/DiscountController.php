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
            'discount_amount' => 'required|numeric|min:0|max:100'
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

    public function show($id)
    {
        return redirect()->route('admin.discounts.statistics', $id);
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        \Log::info('Discount update called', [
            'id' => $id,
            'method' => $request->method(),
            'all_data' => $request->all()
        ]);

        $discount = Discount::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'discount_name' => 'required|string|max:255',
            'discount_description' => 'nullable|string',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date|after:discount_start_date',
            'discount_amount' => 'required|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            \Log::error('Discount validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
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
        } catch (\Exception $e) {
            \Log::error('Discount update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            
            // Check if any products are using this discount
            $productsUsingDiscount = $discount->variants()->count();
            
            if ($productsUsingDiscount > 0) {
                // Set discount_id to NULL for all products using this discount
                $discount->variants()->update(['discount_id' => null]);
            }
            
            // Delete discount image if exists
            if ($discount->discount_img) {
                $imagePath = public_path('imgs/discount/' . $discount->discount_img);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $discount->delete();

            return redirect()->route('admin.discounts.index')
                ->with('success', 'Xóa khuyến mãi thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.discounts.index')
                ->with('error', 'Có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
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
        $discount = Discount::with(['variants.product'])->findOrFail($id);
        
        // Thống kê số sản phẩm áp dụng
        $totalProducts = $discount->variants()->count();
        $totalProductsWithStock = $discount->variants()
            ->where('product_variant_available', '>', 0)
            ->count();
        
        // Tính toán thống kê
        $stats = [
            'total_products' => $totalProducts,
            'products_with_stock' => $totalProductsWithStock,
            'discount_percentage' => $discount->discount_amount,
            'start_date' => $discount->discount_start_date,
            'end_date' => $discount->discount_end_date,
            'days_remaining' => now()->diffInDays($discount->discount_end_date, false),
            'is_active' => $discount->isActive(),
            'status' => $discount->isActive() ? 'Đang áp dụng' : 
                       (now()->lessThan($discount->discount_start_date) ? 'Sắp diễn ra' : 'Đã kết thúc')
        ];

        return view('admin.discounts.statistics', compact('discount', 'stats'));
    }
}