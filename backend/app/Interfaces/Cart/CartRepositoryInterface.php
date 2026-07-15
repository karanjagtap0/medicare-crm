<?php

namespace App\Interfaces\Cart;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function getActiveCart(int $customerId): ?Cart;
    public function createCart(array $data): Cart;
    public function updateCart(Cart $cart, array $data): Cart;
    public function findCartItemByBatch(Cart $cart, int $batchId): ?CartItem;
    public function createCartItem(Cart $cart, array $data): CartItem;
    public function updateCartItem(CartItem $cartItem, array $data): CartItem;
    public function deleteCartItem(CartItem $cartItem): bool;
    public function clearCartItems(Cart $cart): bool;
    public function getCartItem(int $id): CartItem;
}
