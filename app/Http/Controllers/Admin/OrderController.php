<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Search by order number or customer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payments']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Update timestamps based on status
        if ($request->status == 'shipped' && $oldStatus != 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($request->status == 'delivered' && $oldStatus != 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $order->update(['notes' => $request->notes]);

        return back()->with('success', 'Order note added successfully.');
    }

    public function print(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.print', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product', 'payments']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be deleted.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
