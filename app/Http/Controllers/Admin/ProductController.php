<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductVariant;
use App\Models\ProductImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'variants', 'images']);

        // Lọc theo trạng thái (0: ẩn, 1: hiển thị)
        if ($request->filled('status')) {
            $query->where('product_is_display', $request->status);
        } else {
            // Mặc định chỉ hiển thị sản phẩm đang hiển thị (status = 1)
            $query->where('product_is_display', 1);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('product_id', 'desc')->paginate(10);
        $categories = Category::where('category_is_display', 1)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('category_is_display', 1)->get();
        $suppliers = Supplier::where('supplier_is_display', 1)->get();
        
        return view('admin.products.create', compact('categories', 'suppliers'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'product_description' => 'nullable',
            'product_period' => 'nullable|integer|min:1|max:60',
            
            // Avatar image
            'product_avt_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.available' => 'required|integer|min:0',
            
            // Additional Images
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
            'variants.required' => 'Phải có ít nhất 1 biến thể sản phẩm',
            'variants.*.name.required' => 'Tên biến thể không được để trống',
            'variants.*.price.required' => 'Giá không được để trống',
            'variants.*.available.required' => 'Số lượng không được để trống',
        ]);
     $category = Category::where('category_id', $request->category_id)
                           ->where('category_is_display', 1)
                           ->first();
        
        if (!$category) {
            return back()->withErrors(['category_id' => 'Danh mục này không khả dụng'])->withInput();
        }

        $supplier = Supplier::where('supplier_id', $request->supplier_id)
                           ->where('supplier_is_display', 1)
                           ->first();
        
        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'Nhà cung cấp này không khả dụng'])->withInput();
        }
        DB::beginTransaction();
        try {
            // Tạo product_id mới
            $lastProduct = Product::orderBy('product_id', 'desc')->first();
            $newProductId = $lastProduct ? $lastProduct->product_id + 1 : 1;

            // Tạo folder cho sản phẩm: P{id}
            $productFolder = "P{$newProductId}";
            $uploadPath = public_path("imgs/product_image/{$productFolder}");
            
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Upload ảnh đại diện
            $avtImgName = 'default.png';
            if ($request->hasFile('product_avt_img')) {
                $avtImg = $request->file('product_avt_img');
                $avtImgName = "{$productFolder}_avt.{$avtImg->getClientOriginalExtension()}";
                $avtImg->move($uploadPath, $avtImgName);
            }

            // Tạo sản phẩm
            $product = Product::create([
                'product_id' => $newProductId,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'product_name' => $request->product_name,
                'product_avt_img' => $avtImgName,
                'product_description' => $request->product_description,
                'product_period' => $request->product_period ?? 12,
                'product_rate' => 0,
                'product_view_count' => 0,
                'product_is_display' => $request->has('product_is_display') ? 1 : 0,
            ]);

            // Tạo variants
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->product_id,
                        'product_variant_name' => $variant['name'],
                        'product_variant_price' => $variant['price'],
                        'product_variant_available' => $variant['available'],
                        'product_variant_is_stock' => $variant['available'] > 0 ? 1 : 0,
                        'product_variant_is_bestseller' => 0,
                        'product_variant_added_date' => date('Y-m-d'),
                        'product_variant_is_display' => 1,
                    ]);
                }
            }

            // Upload thêm hình ảnh
            if ($request->hasFile('images')) {
                $imageCount = 1;
                foreach ($request->file('images') as $image) {
                    $imageName = "{$productFolder}_{$imageCount}.{$image->getClientOriginalExtension()}";
                    $image->move($uploadPath, $imageName);
                    
                    // Lấy image_id lớn nhất hiện tại
                    $lastImageId = ProductImg::max('image_id') ?? 0;
                    
                    ProductImg::create([
                        'image_id' => $lastImageId + 1,
                        'product_id' => $product->product_id,
                        'image_name' => $imageName,
                        'image_is_display' => 1,
                    ]);
                    $imageCount++;
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with(['category', 'supplier', 'variants', 'images'])
                         ->where('product_is_display', '!=', 2)
                         ->findOrFail($id);
        $categories = Category::where('category_is_display', 1)->get();
        $suppliers = Supplier::where('supplier_is_display', 1)->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'product_description' => 'nullable',
            'product_period' => 'nullable|integer|min:1|max:60',
            'product_avt_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Variants update
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,product_variant_id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.available' => 'required|integer|min:0',
        ]);
    $category = Category::where('category_id', $request->category_id)
                           ->where('category_is_display', 1)
                           ->first();
        
        if (!$category) {
            return back()->withErrors(['category_id' => 'Danh mục này không khả dụng'])->withInput();
        }

        $supplier = Supplier::where('supplier_id', $request->supplier_id)
                           ->where('supplier_is_display', 1)
                           ->first();
        
        if (!$supplier) {
            return back()->withErrors(['supplier_id' => 'Nhà cung cấp này không khả dụng'])->withInput();
        }
        DB::beginTransaction();
        try {
            $productFolder = "P{$product->product_id}";
            $uploadPath = public_path("imgs/product_image/{$productFolder}");

            // Upload ảnh mới nếu có
            if ($request->hasFile('product_avt_img')) {
                // Xóa ảnh cũ nếu không phải default
                if ($product->product_avt_img != 'default.png') {
                    $oldImagePath = $uploadPath . '/' . $product->product_avt_img;
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                
                $avtImg = $request->file('product_avt_img');
                $avtImgName = "{$productFolder}_avt.{$avtImg->getClientOriginalExtension()}";
                $avtImg->move($uploadPath, $avtImgName);
                $product->product_avt_img = $avtImgName;
            }

            // Update sản phẩm
            $product->update([
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'product_period' => $request->product_period ?? 12,
                'product_is_display' => $request->has('product_is_display') ? 1 : 0,
            ]);

            // Update/Create variants
            $variantIds = [];
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id']) && $variantData['id']) {
                    // Update existing variant
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'product_variant_name' => $variantData['name'],
                            'product_variant_price' => $variantData['price'],
                            'product_variant_available' => $variantData['available'],
                            'product_variant_is_stock' => $variantData['available'] > 0 ? 1 : 0,
                        ]);
                        $variantIds[] = $variant->product_variant_id;
                    }
                } else {
                    // Create new variant
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->product_id,
                        'product_variant_name' => $variantData['name'],
                        'product_variant_price' => $variantData['price'],
                        'product_variant_available' => $variantData['available'],
                        'product_variant_is_stock' => $variantData['available'] > 0 ? 1 : 0,
                        'product_variant_is_bestseller' => 0,
                        'product_variant_added_date' => date('Y-m-d'),
                        'product_variant_is_display' => 1,
                    ]);
                    $variantIds[] = $newVariant->product_variant_id;
                }
            }

            // Ẩn variants không còn trong danh sách (không xóa)
            ProductVariant::where('product_id', $product->product_id)
                ->whereNotIn('product_variant_id', $variantIds)
                ->update(['product_variant_is_display' => 0]);

            // Upload new images
            if ($request->hasFile('images')) {
                $existingImages = ProductImg::where('product_id', $product->product_id)->count();
                $imageCount = $existingImages + 1;
                
                foreach ($request->file('images') as $image) {
                    $imageName = "{$productFolder}_{$imageCount}.{$image->getClientOriginalExtension()}";
                    $image->move($uploadPath, $imageName);
                    
                    // Lấy image_id lớn nhất hiện tại
                    $lastImageId = ProductImg::max('image_id') ?? 0;
                    
                    ProductImg::create([
                        'image_id' => $lastImageId + 1,
                        'product_id' => $product->product_id,
                        'image_name' => $imageName,
                        'image_is_display' => 1,
                    ]);
                    $imageCount++;
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Ẩn sản phẩm (soft delete)
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Ẩn sản phẩm: đặt product_is_display = 0
            $product->update(['product_is_display' => 0]);
            
            // Ẩn tất cả variants
            $product->variants()->update(['product_variant_is_display' => 0]);
            
            // Ẩn tất cả images
            $product->images()->update(['image_is_display' => 0]);

            return redirect()->route('admin.products.index')->with('success', 'Đã ẩn sản phẩm!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Khôi phục sản phẩm đã ẩn
     */
    /**
     * Khôi phục sản phẩm đã ẩn
     */
    public function restore($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Hiển thị lại sản phẩm: đặt product_is_display = 1
            $product->update(['product_is_display' => 1]);
            
            // Hiển thị lại variants
            $product->variants()->update(['product_variant_is_display' => 1]);
            
            // Hiển thị lại images
            $product->images()->update(['image_is_display' => 1]);

            return redirect()->route('admin.products.index')
                            ->with('success', 'Đã khôi phục sản phẩm!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Xóa vĩnh viễn (dùng khi cần thiết)
     */
    public function forceDelete($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Đường dẫn folder
            $productFolder = "P{$product->product_id}";
            $uploadPath = public_path("imgs/product_image/{$productFolder}");
            
            // Xóa toàn bộ folder ảnh
            if (File::exists($uploadPath)) {
                File::deleteDirectory($uploadPath);
            }
            
            // Xóa images
            $product->images()->delete();
            
            // Xóa variants
            $product->variants()->delete();
            
            // Xóa details
            $product->details()->delete();
            
            // Xóa product
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa vĩnh viễn!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage($imageId)
    {
        try {
            $image = ProductImg::findOrFail($imageId);
            $product = Product::findOrFail($image->product_id);
            
            $productFolder = "P{$product->product_id}";
            $imagePath = public_path("imgs/product_image/{$productFolder}/{$image->image_name}");
            
            // Xóa file ảnh
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            
            // Xóa record
            $image->delete();
            
            return response()->json(['success' => true, 'message' => 'Ảnh đã được xóa']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa ảnh'], 500);
        }
    }

    /**
     * Danh sách sản phẩm đã xóa (Thùng rác)
     */
    public function trash()
    {
        $products = Product::with(['category', 'supplier', 'variants', 'images'])
                          ->where('product_is_display', 2)
                          ->orderBy('product_id', 'desc')
                          ->paginate(10);
        
        return view('admin.products.trash', compact('products'));
    }
}
