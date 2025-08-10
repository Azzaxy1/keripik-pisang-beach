<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CustomerOrderController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Mark order as completed by customer
   */
  public function markAsCompleted(Order $order)
  {
    // Verify that the order belongs to the current user
    if ($order->user_id !== Auth::id()) {
      return redirect()->route('orders.index')->with('error', 'Order tidak ditemukan.');
    }

    // Check if order can be completed
    if (!$order->canBeCompletedByCustomer()) {
      return redirect()->route('orders.show', $order->id)
        ->with('error', 'Order belum bisa dikonfirmasi diterima. Pastikan pesanan sudah dikirim oleh penjual.');
    }

    // Mark as completed
    $order->markAsCompleted();

    return redirect()->route('orders.show', $order->id)
      ->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');
  }
}
