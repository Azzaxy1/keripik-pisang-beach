<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Hitung total untuk keripik pisang
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $tax = 0; // Tidak ada pajak untuk keripik pisang
        $shipping = $subtotal >= 100000 ? 0 : 15000; // Gratis ongkir untuk pembelian di atas 100rb
        $total = $subtotal + $tax + $shipping;

        // Info rekening BCA
        $bankAccount = [
            'bank_name' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'Keripik Pisang Cinangka'
        ];

        return view('frontend.checkout-keripik', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'bankAccount'));
    }

    /**
     * Proses saat user klik "Place Order"
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'order_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong.');
        }

        DB::beginTransaction();

        try {
            // Handle upload bukti pembayaran
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Hitung totals
            $cartItems = $cart->items()->with('product')->get();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $taxAmount = 0; // Tidak ada pajak
            $shippingAmount = $subtotal >= 100000 ? 0 : 15000; // Gratis ongkir di atas 100rb
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
                'shipping_address_id' => null,
                'billing_address_id' => null,
                'shipping_address' => $customerData,
                'billing_address' => $customerData,
                'notes' => $request->order_notes,
                'currency' => 'IDR'
            ]);

            // Simpan order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity
                ]);
            }

            // Kosongkan keranjang setelah order berhasil
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat dengan nomor: ' . $orderNumber . '. Menunggu konfirmasi pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
