<?php

namespace App\Services\Cart;

use App\Interfaces\Cart\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\MedicineBatch;

class CartService
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function getOrCreateActiveCart(int $customerId): Cart
    {
        $cart = $this->cartRepository->getActiveCart($customerId);

        if (!$cart) {
            $cart = $this->cartRepository->createCart([
                'customer_id' => $customerId,
                'status' => 'Active',
                'sub_total' => 0,
                'tax' => 0,
                'discount' => 0,
                'grand_total' => 0,
            ]);
        }

        return $cart;
    }

    public function addItemToCart(int $customerId, array $data): Cart
    {
        $cart = $this->getOrCreateActiveCart($customerId);
        
        $batch = MedicineBatch::findOrFail($data['medicine_batch_id']);
        $price = $batch->selling_price;
        $quantity = $data['quantity'];
        $total = $price * $quantity;

        $cartItem = $this->cartRepository->findCartItemByBatch($cart, $batch->id);

        if ($cartItem) {
            $this->cartRepository->updateCartItem($cartItem, [
                'quantity' => $cartItem->quantity + $quantity,
                'total' => ($cartItem->quantity + $quantity) * $price,
            ]);
        } else {
            $this->cartRepository->createCartItem($cart, [
                'medicine_id' => $data['medicine_id'],
                'medicine_batch_id' => $batch->id,
                'quantity' => $quantity,
                'price' => $price,
                'tax' => 0,
                'discount' => 0,
                'total' => $total,
            ]);
        }

        return $this->recalculateCart($cart);
    }

    public function updateCartItem(int $id, array $data): Cart
    {
        $cartItem = $this->cartRepository->getCartItem($id);
        
        $this->cartRepository->updateCartItem($cartItem, [
            'quantity' => $data['quantity'],
            'total' => $data['quantity'] * $cartItem->price,
        ]);

        return $this->recalculateCart($cartItem->cart);
    }

    public function removeCartItem(int $id): Cart
    {
        $cartItem = $this->cartRepository->getCartItem($id);
        $cart = $cartItem->cart;
        
        $this->cartRepository->deleteCartItem($cartItem);

        return $this->recalculateCart($cart);
    }

    public function clearCart(int $customerId): void
    {
        $cart = $this->cartRepository->getActiveCart($customerId);
        
        if ($cart) {
            $this->cartRepository->clearCartItems($cart);
            $this->recalculateCart($cart);
        }
    }

    public function getCartSummary(int $customerId): ?array
    {
        $cart = $this->cartRepository->getActiveCart($customerId);

        if (!$cart) {
            return null;
        }

        return [
            'total_items' => $cart->items()->sum('quantity'),
            'sub_total' => $cart->sub_total,
            'tax' => $cart->tax,
            'discount' => $cart->discount,
            'grand_total' => $cart->grand_total,
        ];
    }

    private function recalculateCart(Cart $cart): Cart
    {
        // Reload items to ensure we have the latest
        $cart->load('items.medicine', 'items.batch');
        
        $subTotal = $cart->items->sum('total');
        $tax = $cart->items->sum('tax');
        $discount = $cart->items->sum('discount');
        $grandTotal = $subTotal + $tax - $discount;

        return $this->cartRepository->updateCart($cart, [
            'sub_total' => $subTotal,
            'tax' => $tax,
            'discount' => $discount,
            'grand_total' => $grandTotal,
        ]);
    }
}
