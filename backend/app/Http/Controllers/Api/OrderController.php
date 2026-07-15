<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()?->can('order.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $orders = Order::with('customer')->paginate($request->per_page ?? 10);

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

        $data = $request->validated();

        $cart = null;
        if (!empty($data['cart_id'])) {
            $cart = Cart::find($data['cart_id']);
        }

        if ($cart) {
            $data['sub_total'] = $cart->sub_total ?? 0;
            $data['discount'] = $cart->discount ?? 0;
            $data['tax'] = $cart->tax ?? 0;
            $data['shipping_charge'] = 0;
            $data['grand_total'] = $cart->grand_total ?? 0;
        } else {
            $data['sub_total'] = 0;
            $data['discount'] = 0;
            $data['tax'] = 0;
            $data['shipping_charge'] = 0;
            $data['grand_total'] = 0;
        }

        $data['order_number'] = 'ORD-' . strtoupper(Str::random(8));
        $data['status'] = 'Pending';
        $data['payment_status'] = 'Pending';

        $order = Order::create($data);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'Pending',
            'remarks' => 'Order created',
            'created_by' => Auth::id(),
        ]);

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

        $order = Order::with(['customer', 'statusHistories.creator'])->findOrFail($id);

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

        $order = Order::findOrFail($id);

        $order->update($request->validated());

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
            'status' => 'required|string|in:Pending,Confirmed,Packed,Shipped,Delivered,Cancelled',
            'remarks' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);

        if ($order->status === $request->status) {
            return response()->json(['success' => false, 'message' => 'Order is already in this status'], 400);
        }

        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
        ]);

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

        $request->validate([
            'remarks' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);

        if ($order->status === 'Cancelled') {
            return response()->json(['success' => false, 'message' => 'Order is already cancelled'], 400);
        }

        $order->update(['status' => 'Cancelled']);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'Cancelled',
            'remarks' => $request->remarks ?? 'Order cancelled by user',
            'created_by' => Auth::id(),
        ]);

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

        $order = Order::findOrFail($id);
        $timeline = $order->statusHistories()->with('creator')->orderBy('created_at', 'desc')->get();

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

        $order = Order::with(['customer', 'cart'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Order invoice retrieved successfully.',
            'data' => $order,
        ], 200);
    }
}
