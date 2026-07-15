<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerService;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        private OrderService $orderService
    ) {}

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Leveraging the existing CustomerService to fetch the profile
        $customer = $this->customerService->getCustomer($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer profile retrieved successfully.',
            'data' => $customer,
        ], 200);
    }

    public function purchaseHistory(Request $request, $id)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Pass customer_id in filters to get orders
        $filters = $request->all();
        $filters['customer_id'] = $id;

        $orders = $this->orderService->getOrders($filters);

        return response()->json([
            'success' => true,
            'message' => 'Customer purchase history retrieved successfully.',
            'data' => $orders,
        ], 200);
    }
}
