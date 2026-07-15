<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentRefund;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()?->can('payment.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $query = Payment::with('order');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('gateway_payment_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully.',
            'data' => $payments,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('payment.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $payment = Payment::with(['order', 'refunds'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully.',
            'data' => $payment,
        ], 200);
    }

    public function orderPayments(Request $request, $order_id)
    {
        if (!$request->user()?->can('payment.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = Order::findOrFail($order_id);
        $payments = $order->payments()->with('refunds')->get();

        return response()->json([
            'success' => true,
            'message' => 'Order payments retrieved successfully.',
            'data' => $payments,
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->user()?->can('payment.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:COD,Razorpay,Stripe,UPI,NetBanking,Card',
        ]);

        $order = Order::findOrFail($data['order_id']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_number' => 'PAY-' . strtoupper(Str::random(8)),
            'payment_method' => $data['payment_method'],
            'amount' => $order->grand_total,
            'currency' => 'INR',
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully.',
            'data' => $payment,
        ], 201);
    }

    public function verify(Request $request)
    {
        if (!$request->user()?->can('payment.verify')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'gateway_payment_id' => 'nullable|string',
            'gateway_order_id' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $payment = Payment::findOrFail($data['payment_id']);
        
        $payment->update([
            'status' => 'Paid',
            'gateway_payment_id' => $data['gateway_payment_id'] ?? $payment->gateway_payment_id,
            'gateway_order_id' => $data['gateway_order_id'] ?? $payment->gateway_order_id,
            'transaction_id' => $data['transaction_id'] ?? $payment->transaction_id,
        ]);

        $payment->order->update(['payment_status' => 'Paid']);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'data' => $payment,
        ], 200);
    }

    public function refund(Request $request)
    {
        if (!$request->user()?->can('payment.refund')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        $payment = Payment::findOrFail($data['payment_id']);

        $refund = PaymentRefund::create([
            'payment_id' => $payment->id,
            'amount' => $data['amount'],
            'reason' => $data['reason'],
        ]);

        $payment->update(['status' => 'Refunded']);
        $payment->order->update(['payment_status' => 'Refunded']);

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully.',
            'data' => $refund,
        ], 201);
    }
}

