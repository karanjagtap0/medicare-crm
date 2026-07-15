<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(private PaymentRepositoryInterface $paymentRepository)
    {
    }

    public function getPayments(array $filters)
    {
        return $this->paymentRepository->getPayments($filters);
    }

    public function getPayment(int $id)
    {
        return $this->paymentRepository->getPayment($id);
    }

    public function getOrderPayments(int $orderId)
    {
        $order = Order::findOrFail($orderId); // Validate order exists
        return $this->paymentRepository->getOrderPayments($order->id);
    }

    public function createPayment(array $data)
    {
        $order = Order::findOrFail($data['order_id']);

        $paymentData = [
            'order_id' => $order->id,
            'payment_number' => 'PAY-' . strtoupper(Str::random(8)),
            'payment_method' => $data['payment_method'],
            'amount' => $order->grand_total,
            'currency' => 'INR',
            'status' => 'Pending',
        ];

        return $this->paymentRepository->createPayment($paymentData);
    }

    public function verifyPayment(array $data)
    {
        $payment = $this->paymentRepository->getPayment($data['payment_id']);
        
        $updateData = [
            'status' => 'Paid',
            'gateway_payment_id' => $data['gateway_payment_id'] ?? $payment->gateway_payment_id,
            'gateway_order_id' => $data['gateway_order_id'] ?? $payment->gateway_order_id,
            'transaction_id' => $data['transaction_id'] ?? $payment->transaction_id,
        ];

        $payment = $this->paymentRepository->updatePayment($payment->id, $updateData);

        // Update related order status
        $payment->order->update(['payment_status' => 'Paid']);

        return $payment;
    }

    public function refundPayment(array $data)
    {
        $payment = $this->paymentRepository->getPayment($data['payment_id']);

        $refund = $this->paymentRepository->createRefund([
            'payment_id' => $payment->id,
            'amount' => $data['amount'],
            'reason' => $data['reason'],
        ]);

        $this->paymentRepository->updatePayment($payment->id, ['status' => 'Refunded']);
        
        // Update related order status
        $payment->order->update(['payment_status' => 'Refunded']);

        return $refund;
    }
}
