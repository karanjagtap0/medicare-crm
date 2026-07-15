<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    public function getPayments(array $filters);
    public function getPayment(int $id);
    public function getOrderPayments(int $orderId);
    public function createPayment(array $data);
    public function updatePayment(int $id, array $data);
    public function createRefund(array $data);
}
