<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Customer;

class CartController extends Controller
{
    /**
     * Get cart count for logged in user
     */
    public function getCartCount(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'count' => 0
                ]);
            }

            // Get customer_id from user
            $customer = Customer::where('user_id', $user->id)->first();

            if (!$customer) {
                return response()->json([
                    'count' => 0
                ]);
            }

            // Count items in cart
            $count = Cart::where('customer_id', $customer->customer_id)->count();

            return response()->json([
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart items for logged in user
     */
    public function getCart(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'items' => [],
                    'total' => 0
                ]);
            }

            $customer = Customer::where('user_id', $user->id)->first();

            if (!$customer) {
                return response()->json([
                    'items' => [],
                    'total' => 0
                ]);
            }

            $cartItems = Cart::where('customer_id', $customer->customer_id)
                ->with(['productVariant.product.images', 'productVariant.discount'])
                ->get();

            $total = 0;
            $items = [];

            foreach ($cartItems as $item) {
                if ($item->productVariant && $item->productVariant->product) {
                    $price = $item->productVariant->product_variant_price;
                    $originalPrice = $price;
                    $discountPercent = 0;

                    // Apply discount if available
                    if ($item->productVariant->discount) {
                        $discountPercent = $item->productVariant->discount->discount_amount;
                        $price = $price * (1 - $discountPercent / 100);
                    }

                    $subtotal = $price * $item->cart_quantity;
                    $total += $subtotal;

                    // Get product image
                    $productImage = $item->productVariant->product->product_avt_img
                        ? 'imgs/product_image/P' . $item->productVariant->product->product_id . '/' . $item->productVariant->product->product_avt_img
                        : 'imgs/default.png';

                    $items[] = [
                        'product_variant_id' => $item->product_variant_id,
                        'product_id' => $item->productVariant->product->product_id,
                        'product_name' => $item->productVariant->product->product_name,
                        'variant_name' => $item->productVariant->product_variant_name,
                        'price' => $price,
                        'original_price' => $originalPrice,
                        'discount_percent' => $discountPercent,
                        'quantity' => $item->cart_quantity,
                        'subtotal' => $subtotal,
                        'image' => $productImage
                    ];
                }
            }

            return response()->json([
                'items' => $items,
                'total' => $total,
                'count' => count($items)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'items' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,product_variant_id',
                'quantity' => 'integer|min:1'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
                ], 401);
            }

            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin khách hàng'
                ], 404);
            }

            $quantity = $request->input('quantity', 1);

            // Check if item already exists in cart
            $existingCart = Cart::where('customer_id', $customer->customer_id)
                ->where('product_variant_id', $request->product_variant_id)
                ->first();

            if ($existingCart) {
                // Update quantity
                $existingCart->cart_quantity += $quantity;
                $existingCart->save();
            } else {
                // Add new item
                Cart::create([
                    'customer_id' => $customer->customer_id,
                    'product_variant_id' => $request->product_variant_id,
                    'cart_quantity' => $quantity,
                    'cart_added_date' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,product_variant_id',
                'quantity' => 'required|integer|min:1'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $cart = Cart::where('customer_id', $customer->customer_id)
                ->where('product_variant_id', $request->product_variant_id)
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart'
                ], 404);
            }

            $cart->cart_quantity = $request->quantity;
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request)
    {
        try {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,product_variant_id'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            Cart::where('customer_id', $customer->customer_id)
                ->where('product_variant_id', $request->product_variant_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
