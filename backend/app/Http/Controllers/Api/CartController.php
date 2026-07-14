<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MedicineBatch;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $cart = Cart::with('items.medicine', 'items.batch')
            ->where('customer_id', $request->customer_id)
            ->where('status', 'Active')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => $request->customer_id,
                'status' => 'Active',
                'sub_total' => 0,
                'tax' => 0,
                'discount' => 0,
                'grand_total' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully.',
            'data' => $cart
        ], 200);
    }

    public function store(StoreCartItemRequest $request)
    {
        $cart = Cart::firstOrCreate(
            ['customer_id' => $request->customer_id, 'status' => 'Active'],
            ['sub_total' => 0, 'tax' => 0, 'discount' => 0, 'grand_total' => 0]
        );

        $batch = MedicineBatch::findOrFail($request->medicine_batch_id);
        $price = $batch->selling_price;
        $quantity = $request->quantity;
        $total = $price * $quantity;

        $cartItem = $cart->items()->where('medicine_batch_id', $request->medicine_batch_id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->total = $cartItem->quantity * $price;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'medicine_id' => $request->medicine_id,
                'medicine_batch_id' => $request->medicine_batch_id,
                'quantity' => $quantity,
                'price' => $price,
                'tax' => 0,
                'discount' => 0,
                'total' => $total,
            ]);
        }

        $this->recalculateCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
            'data' => $cart->load('items.medicine', 'items.batch')
        ], 200);
    }

    public function update(UpdateCartItemRequest $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        $cartItem->quantity = $request->quantity;
        $cartItem->total = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        $this->recalculateCart($cartItem->cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully.',
            'data' => $cartItem->cart->load('items.medicine', 'items.batch')
        ], 200);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;
        
        $cartItem->delete();

        $this->recalculateCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.',
            'data' => $cart->load('items.medicine', 'items.batch')
        ], 200);
    }

    public function clear(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $cart = Cart::where('customer_id', $request->customer_id)
            ->where('status', 'Active')
            ->first();

        if ($cart) {
            $cart->items()->delete();
            $this->recalculateCart($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ], 200);
    }

    public function summary(Request $request)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        
        $cart = Cart::where('customer_id', $request->customer_id)
            ->where('status', 'Active')
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart summary retrieved successfully.',
            'data' => [
                'total_items' => $cart->items()->sum('quantity'),
                'sub_total' => $cart->sub_total,
                'tax' => $cart->tax,
                'discount' => $cart->discount,
                'grand_total' => $cart->grand_total,
            ]
        ], 200);
    }

    private function recalculateCart(Cart $cart)
    {
        $subTotal = $cart->items()->sum('total');
        $tax = $cart->items()->sum('tax');
        $discount = $cart->items()->sum('discount');
        $grandTotal = $subTotal + $tax - $discount;

        $cart->update([
            'sub_total' => $subTotal,
            'tax' => $tax,
            'discount' => $discount,
            'grand_total' => $grandTotal,
        ]);
    }
}
