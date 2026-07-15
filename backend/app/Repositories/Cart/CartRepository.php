<?php

namespace App\Repositories\Cart;

use App\Interfaces\Cart\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;

class CartRepository implements CartRepositoryInterface
{
    public function getActiveCart(int $customerId): ?Cart
    {
        return Cart::with('items.medicine', 'items.batch')
            ->where('customer_id', $customerId)
            ->where('status', 'Active')
            ->first();
    }

    public function createCart(array $data): Cart
    {
        return Cart::create($data);
    }

    public function updateCart(Cart $cart, array $data): Cart
    {
        $cart->update($data);
        return $cart;
    }

    public function findCartItemByBatch(Cart $cart, int $batchId): ?CartItem
    {
        return $cart->items()->where('medicine_batch_id', $batchId)->first();
    }

    public function createCartItem(Cart $cart, array $data): CartItem
    {
        return $cart->items()->create($data);
    }

    public function updateCartItem(CartItem $cartItem, array $data): CartItem
    {
        $cartItem->update($data);
        return $cartItem;
    }

    public function deleteCartItem(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }

    public function clearCartItems(Cart $cart): bool
    {
        return (bool) $cart->items()->delete();
    }

    public function getCartItem(int $id): CartItem
    {
        return CartItem::findOrFail($id);
    }
}
