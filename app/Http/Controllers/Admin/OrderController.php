<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Menampilkan data yang terbaru
        if ($request->has('sort') && $request->sort == 'latest') {
            $query->latest();
        } elseif ($request->has('sort') && $request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $orders = $query->paginate(15);
        
        // Calculate total keseluruhan
        $totalKeseluruhan = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalBulanIni = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Calculate total pemesanan - Semua kecuali yang dibatalkan
        $totalPesananKeseluruhan = Order::where('status', '!=', 'cancelled')->count();
        $totalPesananBulanIni = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate total produk terjual - Yang sudah dibayar dan tidak dibatalkan
        $totalProdukTerjualKeseluruhan = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->with('items')
            ->get()
            ->sum(function($order) {
                return $order->items->sum('quantity');
            });

        $totalProdukTerjualBulanIni = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('items')
            ->get()
            ->sum(function($order) {
                return $order->items->sum('quantity');
            });

        return view('admin.orders.index', compact(
            'orders', 
            'totalKeseluruhan', 
            'totalBulanIni',
            'totalPesananKeseluruhan',
            'totalPesananBulanIni',
            'totalProdukTerjualKeseluruhan',
            'totalProdukTerjualBulanIni'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payments']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Cek apakah user memiliki role admin
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah status pesanan.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Update timestamps based on status
        if ($request->status == 'shipped' && $oldStatus != 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($request->status == 'delivered' && $oldStatus != 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        // Cek apakah user memiliki role admin
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah status pembayaran.');
        }

        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function updateTrackingNumber(Request $request, Order $order)
    {
        // Cek apakah user memiliki role admin
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengubah nomor resi.');
        }

        $request->validate([
            'tracking_number' => 'required|string|max:50'
        ]);

        $order->update(['tracking_number' => $request->tracking_number]);

        return back()->with('success', 'Nomor resi berhasil diperbarui.');
    }

    public function addNote(Request $request, Order $order)
    {
        // Cek apakah user memiliki role admin
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menambah catatan pesanan.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $order->update(['notes' => $request->notes]);

        return back()->with('success', 'Catatan pesanan berhasil disimpan.');
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
        // Cek apakah user memiliki role admin
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus pesanan.');
        }

        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang bisa dihapus.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Laporan hanya menampilkan pesanan yang sudah dibayar dan tidak dibatalkan
        $orders = Order::with(['user', 'items.product'])
            ->where('payment_status', 'paid') // Hanya pesanan yang sudah dibayar
            ->where('status', '!=', 'cancelled') // Tidak termasuk yang dibatalkan
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $totalProductsSold = $orders->sum(function($order) {
            return $order->items->sum('quantity');
        });

        $monthName = now()->month($month)->format('F');

        $pdf = Pdf::loadView('admin.orders.monthly-report', compact(
            'orders', 
            'totalRevenue', 
            'totalOrders',
            'totalProductsSold',
            'month', 
            'year', 
            'monthName'
        ));

        return $pdf->download("laporan-pemesanan-{$monthName}-{$year}.pdf");
    }
}
