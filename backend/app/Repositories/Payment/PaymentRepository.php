<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\PaymentRefund;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getPayments(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $query = Payment::with('order');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('gateway_payment_id', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getPayment(int $id)
    {
        return Payment::with(['order', 'refunds'])->findOrFail($id);
    }

    public function getOrderPayments(int $orderId)
    {
        return Payment::with('refunds')->where('order_id', $orderId)->get();
    }

    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function updatePayment(int $id, array $data)
    {
        $payment = $this->getPayment($id);
        $payment->update($data);
        return $payment;
    }

    public function createRefund(array $data)
    {
        return PaymentRefund::create($data);
    }
}
