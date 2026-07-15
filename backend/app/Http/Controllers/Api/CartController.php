<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $cart = $this->cartService->getOrCreateActiveCart($request->customer_id);

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully.',
            'data' => $cart
        ], 200);
    }

    public function store(StoreCartItemRequest $request)
    {
        $cart = $this->cartService->addItemToCart($request->customer_id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
            'data' => $cart
        ], 200);
    }

    public function update(UpdateCartItemRequest $request, $id)
    {
        $cart = $this->cartService->updateCartItem($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully.',
            'data' => $cart
        ], 200);
    }

    public function destroy($id)
    {
        $cart = $this->cartService->removeCartItem($id);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.',
            'data' => $cart
        ], 200);
    }

    public function clear(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $this->cartService->clearCart($request->customer_id);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ], 200);
    }

    public function summary(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $summary = $this->cartService->getCartSummary($request->customer_id);

        if (!$summary) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart summary retrieved successfully.',
            'data' => $summary
        ], 200);
    }
}
