<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\ProductVariant; // Giả sử bạn có model này

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productVariantId = $request->input('product_variant_id');
        $quantity = $request->input('quantity');

        $cartItem = CartItem::where('customer_id', $userId)
            ->where('product_variant_id', $productVariantId)
            ->first();

        if ($cartItem) {
            $cartItem->cart_quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'customer_id' => $userId,
                'product_variant_id' => $productVariantId,
                'cart_quantity' => $quantity,
                'cart_added_date' => Carbon::now(),
            ]);
        }

        $cartItem->load('productVariant');

        return response()->json([
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cartItem' => $cartItem
        ], 200);
    }

    public function getItems(Request $request)
    {
        $userId = Auth::id();

        $cartItems = CartItem::where('customer_id', $userId)
            ->with([
                'productVariant' => function ($query) {
                    $query->select('id', 'price', 'name'); // Thêm các cột cần thiết
                }
            ])
            ->orderBy('cart_added_date', 'desc')
            ->get();

        $total = $cartItems->sum(function ($item) {
            $price = $item->productVariant->price ?? 0;
            return $price * $item->cart_quantity;
        });

        return response()->json([
            'items' => $cartItems,
            'total' => $total
        ], 200);
    }

    public function updateItemQuantity(Request $request, $productVariantId)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newQuantity = $request->input('quantity');

        $cartItem = CartItem::where('customer_id', $userId)
            ->where('product_variant_id', $productVariantId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Sản phẩm không tìm thấy trong giỏ hàng.'], 404);
        }

        $cartItem->cart_quantity = $newQuantity;
        $cartItem->save();

        $cartItem->load('productVariant');

        return response()->json([
            'message' => 'Số lượng sản phẩm đã được cập nhật!',
            'cartItem' => $cartItem
        ], 200);
    }

    public function removeItem($productVariantId)
    {
        $userId = Auth::id();

        $cartItem = CartItem::where('customer_id', $userId)
            ->where('product_variant_id', $productVariantId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Sản phẩm không tìm thấy trong giỏ hàng.'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'], 200);
    }
}