<?php

namespace App\Services\Order;

use App\Interfaces\Order\OrderRepositoryInterface;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function getOrders(array $filters)
    {
        return $this->orderRepository->getOrders($filters);
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $cart = Cart::where('customer_id', $data['customer_id'])
                ->where('id', $data['cart_id'])
                ->where('status', 'Active')
                ->firstOrFail();

            $orderData = [
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $data['customer_id'],
                'cart_id' => $cart->id,
                'sub_total' => $cart->sub_total,
                'discount' => $cart->discount,
                'tax' => $cart->tax,
                'grand_total' => $cart->grand_total,
                'status' => 'Pending',
                'payment_status' => 'Pending',
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            $order = $this->orderRepository->createOrder($orderData);

            $this->orderRepository->updateOrderStatus($order->id, 'Pending', 'Order created successfully');

            $cart->update(['status' => 'Completed']);

            return $order;
        });
    }

    public function getOrder(int $id)
    {
        return $this->orderRepository->getOrder($id);
    }

    public function updateOrder(int $id, array $data)
    {
        $data['updated_by'] = Auth::id();
        return $this->orderRepository->updateOrder($id, $data);
    }

    public function updateOrderStatus(int $id, string $status, ?string $remarks = null)
    {
        return $this->orderRepository->updateOrderStatus($id, $status, $remarks);
    }

    public function cancelOrder(int $id)
    {
        return $this->orderRepository->updateOrderStatus($id, 'Cancelled', 'Order cancelled by user');
    }

    public function getOrderTimeline(int $id)
    {
        return $this->orderRepository->getOrderTimeline($id);
    }
}
