<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserAddress;
use App\Models\Cart;
use App\Models\CartItem;
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

        // Ambil alamat pengguna
        $addresses = $user->addresses;

        // Ambil cart berdasarkan user
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong.');
        }

        $cartItems = $cart->items()->with('product')->get();

        // Hitung total
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $tax = 0; // Tidak ada pajak untuk keripik pisang
        $shipping = $subtotal >= 100000 ? 0 : 5000; // Gratis ongkir untuk pembelian di atas 100rb
        $total = $subtotal + $tax + $shipping;

        return view('frontend.checkout', compact('addresses', 'cartItems', 'subtotal', 'tax', 'shipping', 'total'));
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
            // Handle alamat shipping
            $shippingAddressId = null;
            $shippingAddressData = null;

            if ($request->address_id === 'new') {
                // Buat alamat baru
                $addressData = [
                    'user_id' => $user->id,
                    'type' => UserAddress::TYPE_SHIPPING,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company' => $request->company,
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country ?? 'Indonesia',
                    'phone' => $request->phone,
                    'is_default' => false
                ];

                // Simpan alamat baru jika user memilih untuk menyimpannya
                if ($request->has('save_address') && $request->save_address) {
                    $shippingAddress = UserAddress::create($addressData);
                    $shippingAddressId = $shippingAddress->id;
                }

                // Data alamat untuk disimpan dalam order (format JSON)
                $shippingAddressData = [
                    'type' => 'shipping',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company' => $request->company,
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country ?? 'Indonesia',
                    'phone' => $request->phone
                ];
            } else {
                // Gunakan alamat yang sudah ada
                $existingAddress = UserAddress::where('id', $request->address_id)
                    ->where('user_id', $user->id)
                    ->first();

                if (!$existingAddress) {
                    throw new \Exception('Alamat tidak ditemukan');
                }

                $shippingAddressId = $existingAddress->id;
                $shippingAddressData = $existingAddress->toArray();
                // Remove timestamps, id, user_id, dan fields yang tidak perlu untuk JSON storage
                unset($shippingAddressData['id'], $shippingAddressData['user_id'], $shippingAddressData['created_at'], $shippingAddressData['updated_at'], $shippingAddressData['is_default']);
            }

            // Hitung totals
            $cartItems = $cart->items()->with('product')->get();
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $taxAmount = $subtotal * 0.18; // 18% tax
            $shippingAmount = $subtotal >= 1000 ? 0 : 50;
            $codCharges = $request->payment_method === 'cod' ? 50 : 0;
            $totalAmount = $subtotal + $taxAmount + $shippingAmount + $codCharges;

            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

            // Ensure order number is unique
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            }

            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'status' => Order::STATUS_PENDING,
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount + $codCharges,
                'discount_amount' => 0,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $request->payment_method,
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $shippingAddressId, // Same as shipping for now
                'shipping_address' => $shippingAddressData,
                'billing_address' => $shippingAddressData,
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

            return redirect()->route('orders.show', $order->id)->with('success', 'Order berhasil dibuat dengan nomor: ' . $orderNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses order: ' . $e->getMessage())->withInput();
        }
    }
}
