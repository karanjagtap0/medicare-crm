<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('order.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $orders = $this->orderService->getOrders($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ], 200);
    }

    public function store(StoreOrderRequest $request)
    {
        if (!$request->user()?->can('order.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $this->orderService->createOrder($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('order.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $this->orderService->getOrder($id);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $order,
        ], 200);
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        if (!$request->user()?->can('order.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $this->orderService->updateOrder($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('order.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Shipped,Delivered,Cancelled,Refunded',
            'remarks' => 'nullable|string'
        ]);

        $order = $this->orderService->updateOrderStatus($id, $request->status, $request->remarks);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'data' => $order,
        ], 200);
    }

    public function cancel(Request $request, $id)
    {
        if (!$request->user()?->can('order.cancel')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $this->orderService->cancelOrder($id);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
            'data' => $order,
        ], 200);
    }

    public function timeline(Request $request, $id)
    {
        if (!$request->user()?->can('order.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $timeline = $this->orderService->getOrderTimeline($id);

        return response()->json([
            'success' => true,
            'message' => 'Order timeline retrieved successfully.',
            'data' => $timeline,
        ], 200);
    }

    public function invoice(Request $request, $id)
    {
        if (!$request->user()?->can('order.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $this->orderService->getOrder($id);

        return response()->json([
            'success' => true,
            'message' => 'Invoice generated successfully.',
            'data' => [
                'invoice_number' => 'INV-' . $order->order_number,
                'date' => $order->created_at->format('Y-m-d'),
                'customer' => $order->customer,
                'items' => $order->cart->items,
                'sub_total' => $order->sub_total,
                'discount' => $order->discount,
                'tax' => $order->tax,
                'grand_total' => $order->grand_total,
            ]
        ], 200);
    }
}
