<?php

namespace App\Interfaces\Order;

interface OrderRepositoryInterface
{
    public function getOrders(array $filters);
    public function createOrder(array $data);
    public function getOrder(int $id);
    public function updateOrder(int $id, array $data);
    public function updateOrderStatus(int $id, string $status, ?string $remarks = null);
    public function getOrderTimeline(int $id);
}
