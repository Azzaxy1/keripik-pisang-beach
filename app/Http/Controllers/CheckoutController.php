<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan halaman checkout
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil cart berdasarkan user
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong.');
        }

        $cartItems = $cart->items()->with('product')->get();

        // Hitung total untuk keripik pisang menggunakan current price (sale price jika ada)
        $subtotal = $cartItems->sum(fn($item) => $item->product->current_price * $item->quantity);
        $tax = 0; // Tidak ada pajak untuk keripik pisang
        $shipping = $subtotal >= 100000 ? 0 : 5000; // Gratis ongkir untuk pembelian di atas 100rb
        $total = $subtotal + $tax + $shipping;

        // Info rekening BCA / PERUBAHAN TRANSFER
        $bankAccount = [
            'bank_name' => 'Dana',
            'account_number' => '081952049181',
            'account_name' => 'Bagus Hernadi'
        ];

        return view('frontend.checkout-keripik', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'bankAccount'));
    }

    /**
     * Proses saat user klik "Place Order"
     */
    public function process(Request $request)
    {
        Log::info('Checkout process started', ['user_id' => Auth::id()]);

        try {
            $request->validate([
                'payment_method' => 'required|in:bank_transfer',
                'payment_proof' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string|max:500',
                'courier_service' => 'required|string',
                'order_notes' => 'nullable|string|max:1000',
            ]);

            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong.');
        }

        // Validasi stok sebelum proses checkout
        $cartItems = $cart->items()->with('product')->get();
        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->stock_quantity < $cartItem->quantity) {
                return redirect()->route('cart.index')->with('error', "Stok {$cartItem->product->name} tidak mencukupi. Stok tersedia: {$cartItem->product->stock_quantity}, diminta: {$cartItem->quantity}");
            }
        }

        DB::beginTransaction();

        try {
            // Handle upload bukti pembayaran
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Hitung totals menggunakan current price (sale price jika ada)
            $cartItems = $cart->items()->with('product')->get();
            $subtotal = $cartItems->sum(fn($item) => $item->product->current_price * $item->quantity);
            $taxAmount = 0; // Tidak ada pajak
            $shippingAmount = $subtotal >= 100000 ? 0 : 5000; // Gratis ongkir di atas 100rb
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Generate order number
            $orderNumber = 'KP-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Ensure order number is unique
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'KP-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Data alamat customer
            $customerData = [
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'address' => $request->customer_address
            ];

            // Info rekening bank BCA
            $bankAccountInfo = [
                'bank_name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'Keripik Pisang Cinangka',
                'total_amount' => $totalAmount
            ];

            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'status' => Order::STATUS_PENDING,
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => 0,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'bank_account_info' => json_encode($bankAccountInfo),
                'courier_service' => $request->courier_service,
                'shipping_address_id' => null,
                'billing_address_id' => null,
                'shipping_address' => $customerData,
                'billing_address' => $customerData,
                'notes' => $request->order_notes,
                'currency' => 'IDR'
            ]);

            // Simpan order items dan kurangi stok produk
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Cek apakah stok mencukupi (double check)
                if ($product->stock_quantity < $cartItem->quantity) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock_quantity}, diminta: {$cartItem->quantity}");
                }

                // Buat order item dengan current price (sale price jika ada)
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->current_price,
                    'subtotal' => $cartItem->product->current_price * $cartItem->quantity
                ]);

                // Kurangi stok produk menggunakan method yang sudah dibuat
                Log::info('Decrementing stock', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_stock' => $product->stock_quantity,
                    'quantity_to_subtract' => $cartItem->quantity
                ]);

                $decrementResult = $product->decrementStock($cartItem->quantity);

                Log::info('Stock decremented', [
                    'result' => $decrementResult,
                    'new_stock' => $product->fresh()->stock_quantity
                ]);
            }

            // Kosongkan keranjang setelah order berhasil
            $cart->items()->delete();
            $cart->updateTotals();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat dengan nomor: ' . $orderNumber . '. Menunggu konfirmasi pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
