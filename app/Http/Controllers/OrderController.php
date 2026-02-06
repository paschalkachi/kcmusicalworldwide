<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\LagosLocation;
use App\Models\Product;
use App\Models\ShippingClass;
use App\Models\State;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\PaystackService;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    protected $paystack;
    protected $inventoryService;

   public function __construct(PaystackService $paystack, InventoryService $inventoryService)
    {
        $this->paystack = $paystack;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Place an order (COD or Paystack)
     */
   public function store(Request $request)
    {
      
        try {
            $request->validate([
                'payment_method' => 'required|in:cod,paystack',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'You must be logged in.'], 401);
            }

            // Use items from the request instead of session to ensure consistency
            $cartItems = $request->items;
            
            // Validate that cart is not empty
            if (!is_array($cartItems) || count($cartItems) === 0) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
            }

            // Check if any product in cart has preorder status and if so, force paystack payment method
            $hasPreorderItems = false;
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock_status === 'preorder') {
                    $hasPreorderItems = true;
                    break;
                }
            }
            
            if ($hasPreorderItems && $request->payment_method === 'cod') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cash on Delivery (COD) is not available for preorder items. Please select Paystack as your payment method.'
                ], 422);
            }

            $address = session('checkout_address');
            if (!$address || !$address instanceof Address) {
                // Try to get address from the request if not in session
                $address = Address::find($request->address_id);
                if (!$address) {
                    return response()->json(['success' => false, 'message' => 'Delivery address missing.'], 422);
                }
            }

            $state = State::find($address->state_id);
            if (!$state) {
                return response()->json(['success' => false, 'message' => 'Invalid delivery state.'], 422);
            }

            if ($request->payment_method === 'cod' && strtolower($state->name) !== 'lagos') {
                return response()->json(['success' => false, 'message' => 'COD only available in Lagos.'], 422);
            }

            // -------------------------
            // Calculate subtotal & units
            // -------------------------
            $subtotal = 0;
            $totalUnits = 0;

            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                // Check available quantity (considering reservations)
                $availableQuantity = $product->getAvailableQuantityAttribute();
                if ($product->stock_status === 'outofstock' || $availableQuantity < $item['quantity']) {
                    return response()->json(['success' => false, 'message' => "Insufficient stock for {$product->name}"], 422);
                }

                // Use the price from the request to ensure consistency with frontend
                $price = $item['price'] ?? ($product->sale_price ?? $product->regular_price);
                
                $subtotal += $price * $item['quantity'];
                $totalUnits += $product->shipping_unit * $item['quantity'];
            }

            // -------------------------
            // Shipping
            // -------------------------
            $shippingClass = ShippingClass::where('min_units', '<=', $totalUnits)
                ->where('max_units', '>=', $totalUnits)
                ->first() ?? ShippingClass::orderBy('max_units', 'desc')->first();

            $lagosLocation = null;
            if (strtolower($state->name) === 'lagos') {
                $lagosLocation = LagosLocation::where('name', $address->lga)->first();
                $baseShipping = $lagosLocation->shipping_price ?? 0;
            } else {
                $baseShipping = $state->base_shipping_price;
            }

            $shippingPrice = $baseShipping * $shippingClass->load_factor;
            $tax = 0;
            $total = $subtotal + $shippingPrice + $tax;

           
            DB::beginTransaction();
            try {
                // -------------------------
                // Create Order
                // -------------------------
                $order = Order::create([
                    'user_id' => $user->id,
                    'state_id' => $state->id,
                    'lagos_location_id' => $lagosLocation->id ?? null,
                    'subtotal' => $subtotal,
                    'shipping_price' => $shippingPrice,
                    'tax' => $tax,
                    'total' => $total,
                    'shipping_class_id' => $shippingClass->id,
                    'total_units' => $totalUnits,
                    'status' => 'pending',
                ]);

                // Attach address
                $address->update(['order_id' => $order->id]);

                // Save items - but don't reduce stock yet, only reserve
                foreach ($cartItems as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) continue;
                    $price = $product->sale_price ?? $product->regular_price;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $price * $item['quantity'],
                    ]);
                    
                    // Create reservation for this product based on payment method
                    $reservationTimeInMinutes = $this->getReservationTimeForPaymentMethod($request->payment_method);
                    
                    \App\Models\Reservation::create([
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'quantity' => $item['quantity'],
                        'expires_at' => now()->addMinutes($reservationTimeInMinutes),
                    ]);
                }

                

                // Create transaction
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'reference' => null,
                    'status' => 'pending',
                    'amount' => $total,
                ]);

                //Create reservation
                

                // Reserve inventory based on payment method
                $this->inventoryService->reserveForOrder($order);

                
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e; // Let the outer try-catch handle with debug info
            }

            // -------------------------
            // Payment handling
            // -------------------------
            if ($request->payment_method === 'paystack') {
                $paymentData = [
                    'email' => $user->email,
                    'amount' => $total * 100,
                    'metadata' => [
                        'order_id' => $order->id,
                        'name' => $address->name,
                        'phone' => $address->phone,
                    ],
                    'callback_url' => route('payment.callback'),
                ];

                $response = $this->paystack->initializeTransaction($paymentData);

                $transaction->update(['reference' => $response['data']['reference']]);

                return response()->json([
                    'success' => true,
                    'payment_url' => $response['data']['authorization_url']
                ]);
            }

            // COD - For COD orders, keep the status as pending
            // The inventory is reserved and will be confirmed only when payment is made on delivery
            // This happens when admin updates the order status to 'paid' in the backend
            
            session()->forget(['cart', 'checkout']);

            return response()->json([
                'success' => true,
                'redirect_url' => route('orders.confirmation', $order)
            ]);
        } catch (\Throwable $e) {
            // DEBUG MODE: true if APP_DEBUG = true
            $debug = config('app.debug', false);

            return response()->json([
                'success' => false,
                'message' => 'Order failed.',
                'error' => $debug ? $e->getMessage() : null,
                'file' => $debug ? $e->getFile() : null,
                'line' => $debug ? $e->getLine() : null,
                'trace' => $debug ? $e->getTrace() : null,
            ], 500);
        }
    }

    /**
     * Get reservation time based on payment method
     */
    private function getReservationTimeForPaymentMethod($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'paystack':
                return 30; // 30 minutes for Paystack
            case 'cod':
                return 7 * 24 * 60; // 7 days (in minutes) for COD
            default:
                return 30; // Default to 30 minutes
        }
    }

    // -------------------------
    // Paystack callback
    // -------------------------
    public function paystackCallback(Request $request)
{
    $reference = $request->query('reference');

    if (!$reference) {
       
        return redirect()->route('checkout')
            ->with('error', 'Payment reference missing.');
    }

    $transaction = Transaction::where('reference', $reference)->first();

    if (!$transaction) {
        \Log::error('Paystack callback: Transaction not found', ['reference' => $reference]);
        return redirect()->route('checkout')
            ->with('error', 'Transaction not found.');
    }

    try {
        \Log::info('Paystack callback: Verifying transaction', ['reference' => $reference]);
        $response = $this->paystack->verifyTransaction($reference);

        \Log::info('Paystack callback: Verification response received', [
            'reference' => $reference,
            'response_status' => $response['status'] ?? 'missing',
            'data_status' => $response['data']['status'] ?? 'missing'
        ]);

        // Save full response if column exists
        if (Schema::hasColumn('transactions', 'gateway_response')) {
            $transaction->gateway_response = json_encode($response);
        }

        $order = Order::find($transaction->order_id);

        if ($response['status'] && $response['data']['status'] === 'success') {
            \Log::info('Paystack callback: Transaction successful', ['reference' => $reference]);
            $transaction->status = 'approved';
            $order->status = 'paid';  // This will trigger the observer to confirm inventory
            $order->save();
            $transaction->reference = $reference;
            $transaction->paid_at = now();
            $transaction->save();

            session()->forget(['cart', 'checkout']);

            $order = Order::find($transaction->order_id);
            
            // Store the order ID in session so the user can access the confirmation page
            session(['current_viewed_order_id' => $order->id]);
            
            // Redirect to order confirmation page
            return redirect()->route('orders.confirmation', $order)
                             ->with('success', 'Payment successful!');
        } else {
            \Log::warning('Paystack callback: Transaction failed', [
                'reference' => $reference,
                'response' => $response
            ]);
            $transaction->status = 'declined';
            $transaction->save();

            return redirect()->route('checkout')
                             ->with('error', 'Payment failed or cancelled. Response: ' . json_encode($response));
        }

    } catch (\Exception $e) {
        \Log::error('Paystack callback: Exception occurred', [
            'reference' => $reference,
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('checkout')
                         ->with('error', 'Payment verification failed: ' . $e->getMessage());
    }
}

    /**
     * Order confirmation / invoice page
     */
    public function confirmation(Order $order)
    {
        
        // If the user is authenticated, ensure the order belongs to them
        if(auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to view this order');
        }

        // If the user is not authenticated but the order has a user, 
        // we might be coming from Paystack callback for a guest checkout
        // In this case, allow access only for the current session
        $orderInSession = session('current_viewed_order_id');
        
        if (!auth()->check() && $order->user_id) {
            // If it's a registered user's order and we're not authenticated, 
            // only allow access if it was stored in session during the checkout process
            if ($orderInSession != $order->id) {
                abort(403, 'Unauthorized to view this order');
            }
        }

        // Load all required relationships for the order
        $order->loadMissing(['items.product', 'transaction']);
        
        // Explicitly load the address relationship
        $order->load(['address.state']);

        // Store the viewed order in session to allow continued access during this session
        session(['current_viewed_order_id' => $order->id]);

        return view('user.pages.order-confirmation', compact('order'));
    }
}
