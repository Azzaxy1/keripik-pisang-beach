<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpayId;
    private $razorpayKey;

    public function __construct()
    {
        $this->middleware('auth');
        $this->razorpayId = env('RAZORPAY_KEY');
        $this->razorpayKey = env('RAZORPAY_SECRET');
    }

    public function razorpay($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->firstOrFail();

        if ($order->payment_status === 'completed') {
            return redirect()->route('order.success', $order->id);
        }

        // Create Razorpay order
        $api = new Api($this->razorpayId, $this->razorpayKey);
        
        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => $order->total_amount * 100, // Amount in paise
            'currency' => 'INR',
            'notes' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id
            ]
        ]);

        $data = [
            'order' => $order,
            'razorpayOrder' => $razorpayOrder,
            'razorpayId' => $this->razorpayId,
            'amount' => $order->total_amount * 100,
            'currency' => 'INR',
            'name' => config('app.name'),
            'description' => 'Order Payment',
            'prefill' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'contact' => $order->user->phone ?? ''
            ]
        ];

        return view('frontend.payment.razorpay', $data);
    }

    public function razorpayCallback(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('id', $request->order_id)
            ->firstOrFail();

        try {
            $api = new Api($this->razorpayId, $this->razorpayKey);
            
            // Verify payment signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Fetch payment details
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if ($payment->status === 'captured') {
                // Payment successful
                $order->update([
                    'payment_status' => 'completed',
                    'payment_id' => $request->razorpay_payment_id,
                    'status' => 'processing'
                ]);

                // Create payment record
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'amount' => $payment->amount / 100,
                    'currency' => $payment->currency,
                    'status' => 'completed',
                    'payment_method' => 'razorpay',
                    'transaction_id' => $payment->id,
                    'gateway' => 'razorpay',
                    'gateway_response' => $payment->toArray(),
                    'processed_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful',
                    'redirect_url' => route('order.success', $order->id)
                ]);
            } else {
                throw new \Exception('Payment not captured');
            }

        } catch (\Exception $e) {
            // Payment failed
            $order->update(['payment_status' => 'failed']);

            Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'status' => 'failed',
                'payment_method' => 'razorpay',
                'transaction_id' => $request->razorpay_payment_id ?? null,
                'gateway' => 'razorpay',
                'gateway_response' => ['error' => $e->getMessage()],
                'processed_at' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
                'redirect_url' => route('payment.failed', $order->id)
            ]);
        }
    }

    public function failed($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->firstOrFail();

        return view('frontend.payment.failed', compact('order'));
    }

    public function retry($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->where('payment_status', 'failed')
            ->firstOrFail();

        return redirect()->route('payment.razorpay', $order->id);
    }
}
