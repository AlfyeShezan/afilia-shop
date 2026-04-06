<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $notification = new Notification();
            
            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            $order = Order::where('order_number', $orderId)->firstOrFail();

            if ($status == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing'
                        ]);
                        $this->handlePostPayment($order);
                    }
                }
            } elseif ($status == 'settlement') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                $this->handlePostPayment($order);
            } elseif ($status == 'pending') {
                $order->update(['payment_status' => 'pending']);
            } elseif ($status == 'deny') {
                $order->update(['payment_status' => 'failed']);
            } elseif ($status == 'expire') {
                $order->update(['payment_status' => 'expired']);
            } elseif ($status == 'cancel') {
                $order->update(['payment_status' => 'cancelled']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function handlePostPayment($order)
    {
        // Prevent duplicate processing if already handled
        if ($order->status === 'processing' && $order->getAttribute('payment_status_was_paid')) {
             return;
        }

        foreach ($order->items as $item) {
            $vendor = $item->vendor;
            if ($vendor) {
                // net_amount was calculated during order creation
                $vendor->increment('balance', $item->net_amount);
            }
        }
    }
}
