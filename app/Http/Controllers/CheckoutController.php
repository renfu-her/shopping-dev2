<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $member = auth()->guard('member')->user();
        $cartService = app(CartService::class);
        $cartItems = $cartService->getCart();
        
        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = $subtotal * 0.05; // 5% tax
        $shipping = 0; // Free shipping
        $total = $subtotal + $tax + $shipping;
        
        return view('pages.checkout.index', compact('member', 'cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'billing_address' => 'required|string',
            'payment_method' => 'required|in:credit_card,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        // try {
            // Get cart items
            $cartItems = app(CartService::class)->getCart();
            
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $tax = $subtotal * 0.05; // 5% tax
            $shipping = 0; // Free shipping for now
            $total = $subtotal + $tax + $shipping;

            // Generate order number with sequence
            $today = date('Ymd');
            $lastOrder = Order::where('order_number', 'like', 'ORDER' . $today . '%')
                ->orderBy('order_number', 'desc')
                ->first();
            
            if ($lastOrder) {
                // Extract sequence number from last order
                $lastSequence = (int)substr($lastOrder->order_number, -4);
                $sequence = $lastSequence + 1;
            } else {
                $sequence = 1;
            }
            
            $orderNumber = 'ORDER' . $today . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'member_id' => auth()->guard('member')->id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'shipping_address' => $request->billing_address, // For now, use billing address
                'billing_address' => $request->billing_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Process payment based on method
            if ($request->payment_method === 'credit_card') {
                // Here you would integrate with a real payment gateway
                // For now, we'll simulate a successful payment
                $paymentResult = $this->processCreditCardPayment($request, $total, $order);
                
                if ($paymentResult['success']) {
                    if (isset($paymentResult['redirect']) && $paymentResult['redirect']) {
                        // Redirect to ECPay payment gateway
                        return $this->redirectToECPay();
                    } else {
                        $order->update([
                            'payment_status' => 'paid',
                            'ecpay_payment_date' => now(),
                        ]);
                        
                        // Clear cart
                        app(CartService::class)->clearCart();
                        
                        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Payment processed.');
                    }
                } else {
                    $order->update(['payment_status' => 'failed']);
                    return redirect()->back()->with('error', 'Payment failed: ' . $paymentResult['message']);
                }
            } else {
                // Bank transfer - mark as pending
                $order->update(['payment_status' => 'pending']);
                
                // Clear cart
                app(CartService::class)->clearCart();
                
                return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Please complete bank transfer.');
            }

        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'An error occurred while processing your order. Please try again.');
        // }
    }

    private function processCreditCardPayment(Request $request, $amount, $order)
    {
        // ECPay Configuration - Test Environment
        $merchantId = '3002607'; // Test environment merchant ID
        $hashKey = 'pwFHCqoQZGmho4w6'; // Test environment hash key
        $hashIV = 'EkRm7iFT261dpevs'; // Test environment hash IV
        $isProduction = false; // Test environment

        // Debug: Log configuration
        Log::info('ECPay Config:', [
            'merchantId' => $merchantId,
            'hashKey' => $hashKey,
            'hashIV' => $hashIV,
            'isProduction' => $isProduction
        ]);

        // ECPay API URL
        $baseUrl = $isProduction 
            ? 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5'
            : 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

        // Prepare payment data
        $data = [
            'MerchantID' => $merchantId,
            'MerchantTradeNo' => $order->order_number,
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => (int)$amount,
            'TradeDesc' => '商品購買',
            'ItemName' => $this->formatItemNames($order),
            'ReturnURL' => route('payment.result'),
            'ClientBackURL' => route('orders.show', $order),
            'OrderResultURL' => route('payment.result'),
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,
        ];

        // Generate CheckMacValue
        $data['CheckMacValue'] = $this->generateCheckMacValue($data, $hashKey, $hashIV);


        // Debug: Log the data being sent to ECPay
        Log::info('ECPay Payment Data:', $data);

        // Store payment data in session for redirect
        session(['ecpay_payment_data' => $data]);
        session(['ecpay_payment_url' => $baseUrl]);

        return ['success' => true, 'message' => 'Redirecting to payment gateway', 'redirect' => true];
    }

    private function formatItemNames($order)
    {
        $itemNames = $order->items->map(function ($item) {
            return $item->product_name . ' x' . $item->quantity;
        })->toArray();
        
        // Limit to 400 characters as per ECPay requirements
        $itemString = implode('#', $itemNames);
        if (strlen($itemString) > 400) {
            $itemString = substr($itemString, 0, 400);
        }
        
        return $itemString;
    }

    private function generateCheckMacValue($data, $hashKey, $hashIV)
    {
        // Remove CheckMacValue if exists
        unset($data['CheckMacValue']);

        // Sort by key (natural sort like the official SDK)
        ksort($data, SORT_NATURAL);

        // Build the combined string like the official SDK
        $combined = 'HashKey=' . $hashKey;
        foreach ($data as $name => $value) {
            $combined .= '&' . $name . '=' . $value;
        }
        $combined .= '&HashIV=' . $hashIV;

        // URL encode
        $encoded = urlencode($combined);

        // Convert to lowercase
        $encoded = strtolower($encoded);

        // Generate SHA256 hash
        $hash = hash('sha256', $encoded);

        // Return uppercase
        return strtoupper($hash);
    }

    private function redirectToECPay()
    {
        $paymentData = session('ecpay_payment_data');
        $paymentUrl = session('ecpay_payment_url');

        if (!$paymentData || !$paymentUrl) {
            return redirect()->back()->with('error', 'Payment data not found.');
        }

        // Clear session data
        session()->forget(['ecpay_payment_data', 'ecpay_payment_url']);

        // Return view with auto-submit form
        return view('pages.checkout.ecpay-redirect', [
            'paymentUrl' => $paymentUrl,
            'paymentData' => $paymentData
        ]);
    }

    public function paymentResult(Request $request)
    {
        // ECPay Configuration - Test Environment
        $merchantId = '3002607';
        $hashKey = 'pwFHCqoQZGmho4w6';
        $hashIV = 'EkRm7iFT261dpevs';

        // Verify CheckMacValue
        $receivedCheckMacValue = $request->input('CheckMacValue');
        $data = $request->except('CheckMacValue');
        $calculatedCheckMacValue = $this->generateCheckMacValue($data, $hashKey, $hashIV);

        if ($receivedCheckMacValue !== $calculatedCheckMacValue) {
            return response()->json(['status' => 'error', 'message' => 'CheckMacValue verification failed']);
        }

        // Get order details
        $merchantTradeNo = $request->input('MerchantTradeNo');
        $paymentDate = $request->input('PaymentDate');
        $paymentType = $request->input('PaymentType');
        $paymentTypeChargeFee = $request->input('PaymentTypeChargeFee');
        $rtnCode = $request->input('RtnCode');
        $rtnMsg = $request->input('RtnMsg');
        $simulatePaid = $request->input('SimulatePaid');

        // Find the order
        $order = Order::where('order_number', $merchantTradeNo)->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found']);
        }

        // Update order based on payment result
        if ($rtnCode == '1') {
            // Payment successful
            $order->update([
                'payment_status' => 'paid',
                'ecpay_payment_date' => $paymentDate ? \Carbon\Carbon::createFromFormat('Y/m/d H:i:s', $paymentDate) : now(),
                'ecpay_merchant_trade_no' => $merchantTradeNo,
            ]);

            // Clear cart
            app(CartService::class)->clearCart();

            return response()->json(['status' => 'success', 'message' => 'Payment successful']);
        } else {
            // Payment failed
            $order->update([
                'payment_status' => 'failed',
            ]);

            return response()->json(['status' => 'error', 'message' => $rtnMsg]);
        }
    }

    public function paymentNotify(Request $request)
    {
        // Handle server-to-server notification from ECPay
        // This is called by ECPay's server, not the client
        return $this->paymentResult($request);
    }
}
