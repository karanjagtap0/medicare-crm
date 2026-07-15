<?php

namespace App\Repositories\Order;

use App\Interfaces\Order\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements OrderRepositoryInterface
{
    public function getOrders(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        $query = Order::with('customer');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function getOrder(int $id)
    {
        return Order::with(['customer', 'cart.items.medicine', 'cart.items.batch', 'statusHistories', 'payments'])->findOrFail($id);
    }

    public function updateOrder(int $id, array $data)
    {
        $order = $this->getOrder($id);
        $order->update($data);
        return $order;
    }

    public function updateOrderStatus(int $id, string $status, ?string $remarks = null)
    {
        $order = $this->getOrder($id);
        
        $order->update([
            'status' => $status,
            'updated_by' => Auth::id(),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $status,
            'remarks' => $remarks ?? "Order status changed to {$status}",
            'created_by' => Auth::id(),
        ]);

        return $order;
    }

    public function getOrderTimeline(int $id)
    {
        $order = $this->getOrder($id);
        return $order->statusHistories()->with('user')->orderBy('created_at', 'desc')->get();
    }
}
