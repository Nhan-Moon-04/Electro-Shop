<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    /**
     * Get all orders for the authenticated user
     */
    public function myOrders(Request $request)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get customer_id from user
            $customer = Customer::where('user_id', $user->user_id)->first();

            if (!$customer) {
                return response()->json([
                    'orders' => []
                ]);
            }

            // Get all orders for this customer
            $orders = Order::where('customer_id', $customer->customer_id)
                ->orderBy('order_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details
     */
    public function show($orderId)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $order = Order::where('order_id', $orderId)
                ->where('customer_id', $customer->customer_id)
                ->with('orderDetails.productVariant.product')
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order
     */
    public function cancel($orderId)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $order = Order::where('order_id', $orderId)
                ->where('customer_id', $customer->customer_id)
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Only allow canceling orders that are waiting for payment
            if ($order->order_status !== 'Chờ thanh toán') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể hủy đơn hàng đang chờ thanh toán'
                ], 400);
            }

            $order->order_status = 'Đã hủy';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
