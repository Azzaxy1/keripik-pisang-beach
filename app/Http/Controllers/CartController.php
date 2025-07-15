<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = [];
        $total = 0;
        
        if ($cart) {
            $cartItems = $cart->items()->with('product.images')->get();
            $total = $cartItems->sum('subtotal');
        }
        
        return view('frontend.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if (!$product->status || $product->stock_status !== 'in_stock') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ]);
        }

        // Check stock quantity
        if ($product->manage_stock && $product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock quantity'
            ]);
        }

        $cart = $this->getOrCreateCart();
        
        // Check if item already exists in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check stock again for total quantity
            if ($product->manage_stock && $product->stock_quantity < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock quantity'
                ]);
            }
            
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->current_price
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->current_price
            ]);
        }

        $cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cart->total_items
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Verify ownership
        $cart = $this->getCart();
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
        }

        $product = $cartItem->product;
        
        // Check stock quantity
        if ($product->manage_stock && $product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock quantity'
            ]);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'price' => $product->current_price
        ]);

        $cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'subtotal' => $cartItem->subtotal,
            'total' => $cart->total_amount,
            'cart_count' => $cart->total_items
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Verify ownership
        $cart = $this->getCart();
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
        }

        $cartItem->delete();
        $cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'total' => $cart->total_amount,
            'cart_count' => $cart->total_items
        ]);
    }

    public function clear()
    {
        $cart = $this->getCart();
        
        if ($cart) {
            $cart->items()->delete();
            $cart->updateTotals();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }

    public function count()
    {
        $cart = $this->getCart();
        $count = $cart ? $cart->total_items : 0;
        
        return response()->json(['count' => $count]);
    }

    private function getCart()
    {
        if (Auth::check()) {
            return Auth::user()->cart;
        }
        
        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)->first();
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Auth::user()->getOrCreateCart();
        }
        
        $sessionId = session()->getId();
        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['total_amount' => 0, 'total_items' => 0]
        );
    }
}
