<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Create a simple order for a single product variant (from product page "Mua")
    public function createOrder(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'customer_id' => 'required|integer',
            'order_name' => 'required|string',
            'order_phone' => 'required|string',
            'order_delivery_address' => 'required|string',
        ]);

        $variant = ProductVariant::find($request->product_variant_id);
        if (!$variant) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        // Use DB transaction to create order and details
        $orderId = null;

        DB::beginTransaction();
        try {
            // Find next order_id (legacy table may not have auto_increment)
            $max = DB::table('orders')->max('order_id');
            $orderId = $max ? $max + 1 : 1;

            $totalBefore = ($variant->product_variant_price ?? 0) * $request->quantity;

            $order = Order::create([
                'order_id' => $orderId,
                'customer_id' => $request->customer_id,
                'staff_id' => 1,
                'order_name' => $request->order_name,
                'order_phone' => $request->order_phone,
                'order_date' => date('Y-m-d'),
                'order_delivery_date' => date('Y-m-d'),
                'order_delivery_address' => $request->order_delivery_address,
                'order_note' => $request->order_note ?? '',
                'order_total_before' => $totalBefore,
                'order_total_after' => $totalBefore,
                'paying_method_id' => $request->paying_method_id ?? 3, // Default COD
                'order_paying_date' => '1970-01-01 00:00:00', // Default date cho chưa thanh toán
                'order_is_paid' => 0,
                'order_status' => 'Chờ thanh toán'
            ]);

            OrderDetail::create([
                'order_id' => $orderId,
                'product_variant_id' => $variant->product_variant_id,
                'order_detail_quantity' => $request->quantity,
                'order_detail_price_before' => $variant->product_variant_price,
                'order_detail_price_after' => $variant->product_variant_price,
            ]);

            DB::commit();

            return response()->json(['order_id' => $orderId], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Show payment page for an order (simple view)
    public function showPayment($orderId)
    {
        $order = Order::where('order_id', $orderId)->first();
        if (!$order) {
            abort(404, 'Order not found');
        }
        return view('payment.payment_page', ['order' => $order]);
    }

    // Show checkout page (mua ngay từ sản phẩm HOẶC từ giỏ hàng)
    public function showCheckout(Request $request)
    {
        $productVariantId = $request->product_variant_id;
        $quantity = $request->quantity ?? 1;

        // If có product_variant_id thì mua 1 sản phẩm (mua ngay)
        if ($productVariantId) {
            $variant = ProductVariant::with([
                'product.images' => function ($query) {
                    $query->where('image_is_display', 1);
                }
            ])->find($productVariantId);

            if (!$variant) {
                return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
            }

            $totalAmount = ($variant->product_variant_price ?? 0) * $quantity;

            return view('checkout.index', [
                'variant' => $variant,
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'type' => 'single' // đánh dấu đây là mua 1 sản phẩm
            ]);
        }

        // Nếu không có product_variant_id thì checkout toàn bộ giỏ hàng
        // Load cart items from session/database
        return view('checkout.index', [
            'type' => 'cart' // đánh dấu đây là mua từ giỏ hàng
        ]);
    }

    // Method showCheckoutCart đã bị xóa - không cần giỏ hàng nữa

    // Webhook endpoint used by payment provider to notify success
    public function webhook(Request $request)
    {
        // For simplicity, accept JSON with { order_id, status }
        $data = $request->only(['order_id', 'status']);
        if (empty($data['order_id']) || empty($data['status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $order = Order::where('order_id', $data['order_id'])->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($data['status'] === 'paid') {
            $order->order_is_paid = 1;
            $order->order_paying_date = date('Y-m-d');
            $order->order_status = 'Đang giao hàng';
            $order->save();
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false, 'msg' => 'unsupported status'], 400);
    }
}
