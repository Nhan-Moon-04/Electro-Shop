<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Lọc theo trạng thái (0: ẩn, 1: hiển thị)
        if ($request->filled('status')) {
            $query->where('supplier_is_display', $request->status);
        } else {
            // Mặc định chỉ hiển thị nhà cung cấp đang hiển thị (status = 1)
            $query->where('supplier_is_display', 1);
        }

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('supplier_name', 'like', '%' . $request->search . '%');
        }

        $suppliers = $query->orderBy('supplier_id', 'desc')->paginate(15);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|max:255|unique:suppliers,supplier_name',
            'supplier_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'supplier_name.required' => 'Tên nhà cung cấp không được để trống',
            'supplier_name.unique' => 'Tên nhà cung cấp đã tồn tại',
            'supplier_logo.image' => 'File phải là ảnh',
        ]);

        // Xử lý upload logo
        $logoName = null;
        if ($request->hasFile('supplier_logo')) {
            $logo = $request->file('supplier_logo');
            $logoName = $logo->getClientOriginalName();
            
            $destinationPath = public_path('imgs/suppliers_logo');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            $logo->move($destinationPath, $logoName);
        }

        Supplier::create([
            'supplier_name' => $validated['supplier_name'],
            'supplier_logo' => $logoName,
            'supplier_is_display' => $request->has('supplier_is_display') ? 1 : 0,
        ]);

        return redirect()->route('admin.suppliers.index')
                        ->with('success', 'Thêm nhà cung cấp thành công!');
    }

    /**
     * Show the form for editing the supplier.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the supplier.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'supplier_name' => 'required|max:255|unique:suppliers,supplier_name,' . $id . ',supplier_id',
            'supplier_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'supplier_name.required' => 'Tên nhà cung cấp không được để trống',
            'supplier_name.unique' => 'Tên nhà cung cấp đã tồn tại',
            'supplier_logo.image' => 'File phải là ảnh',
        ]);

        // Xử lý upload logo mới
        $logoName = $supplier->supplier_logo;
        if ($request->hasFile('supplier_logo')) {
            // Xóa logo cũ
            if ($supplier->supplier_logo) {
                $oldLogoPath = public_path('imgs/suppliers_logo/' . $supplier->supplier_logo);
                if (File::exists($oldLogoPath)) {
                    File::delete($oldLogoPath);
                }
            }

            $logo = $request->file('supplier_logo');
            $logoName = $logo->getClientOriginalName();
            
            $destinationPath = public_path('imgs/suppliers_logo');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            $logo->move($destinationPath, $logoName);
        }

        $supplier->update([
            'supplier_name' => $validated['supplier_name'],
            'supplier_logo' => $logoName,
            'supplier_is_display' => $request->has('supplier_is_display') ? 1 : 0,
        ]);

        return redirect()->route('admin.suppliers.index')
                        ->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    /**
     * Ẩn nhà cung cấp (chuyển sang supplier_is_display = 0)
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Ẩn nhà cung cấp: đặt supplier_is_display = 0
        $supplier->update([
            'supplier_is_display' => 0
        ]);

        return redirect()->route('admin.suppliers.index')
                        ->with('success', 'Đã ẩn nhà cung cấp!');
    }

    /**
     * Khôi phục nhà cung cấp đã ẩn (chuyển sang supplier_is_display = 1)
     */
    public function restore($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Hiển thị lại nhà cung cấp: đặt supplier_is_display = 1
        $supplier->update([
            'supplier_is_display' => 1
        ]);

        return redirect()->route('admin.suppliers.index', ['hidden' => 1])
                        ->with('success', 'Đã khôi phục nhà cung cấp!');
    }
}
