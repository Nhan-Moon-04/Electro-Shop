<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get product details with variants
     */
    public function show($id)
    {
        try {
            $product = Product::with(['variants', 'images'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'product_variant_id' => $variant->product_variant_id,
                        'variant_name' => $variant->product_variant_name,
                        'price' => $variant->product_variant_price,
                        'quantity' => $variant->product_variant_quantity,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }
}
