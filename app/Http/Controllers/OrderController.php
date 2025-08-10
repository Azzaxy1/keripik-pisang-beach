<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payments', 'user']);

        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Download invoice for an order
     */
    public function downloadInvoice(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow download if order is completed/delivered
        if (!in_array($order->status, ['delivered', 'shipped', 'completed'])) {
            return redirect()->back()->with('error', 'Invoice can only be downloaded for completed/delivered orders.');
        }

        $order->load(['items.product', 'payments', 'user']);

        return view('frontend.orders.invoice', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
            'notes' => 'Cancelled by customer'
        ]);

        return redirect()->route('orders.index')->with('success', 'Order cancelled successfully.');
    }
}
