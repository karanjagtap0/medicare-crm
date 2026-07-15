<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\VerifyPaymentRequest;
use App\Http\Requests\RefundPaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('payment.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $payments = $this->paymentService->getPayments($request->all());

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

        $payment = $this->paymentService->getPayment($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully.',
            'data' => $payment,
        ], 200);
    }

    public function orderPayments(Request $request, $orderId)
    {
        if (!$request->user()?->can('payment.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $payments = $this->paymentService->getOrderPayments($orderId);

        return response()->json([
            'success' => true,
            'message' => 'Order payments retrieved successfully.',
            'data' => $payments,
        ], 200);
    }

    public function store(StorePaymentRequest $request)
    {
        if (!$request->user()?->can('payment.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $payment = $this->paymentService->createPayment($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully.',
            'data' => $payment,
        ], 201);
    }

    public function verify(VerifyPaymentRequest $request)
    {
        if (!$request->user()?->can('payment.verify')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $payment = $this->paymentService->verifyPayment($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'data' => $payment,
        ], 200);
    }

    public function refund(RefundPaymentRequest $request)
    {
        if (!$request->user()?->can('payment.refund')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $refund = $this->paymentService->refundPayment($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully.',
            'data' => $refund,
        ], 201);
    }
}

