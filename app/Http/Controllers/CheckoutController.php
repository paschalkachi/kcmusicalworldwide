<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\LagosLocation;
use App\Models\Product;
use App\Models\ShippingClass;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $defaultAddress = Address::where('user_id', Auth::id())->default()->first();
        return view('user.pages.checkout', compact('defaultAddress'));
    }
    
    public function shippingInfo(Request $request)
    {
        
    $request->validate([
        'state' => 'required|string',
        'items' => 'required|array',
        'items.*.product_id' => 'required|integer',
        'items.*.quantity' => 'required|integer|min:1'
    ]);

    // 1️⃣ Base shipping price
    if ($request->state === 'Lagos') {
        $location = LagosLocation::where('name', $request->lga)->firstOrFail();
        $basePrice = $location->shipping_price;
    } else {
        $state = State::where('name', $request->state)->firstOrFail();
        $basePrice = $state->base_shipping_price;
    }

    // 2️⃣ Total units
    $totalUnits = $this->calculateTotalUnits($request->items);

    // 3️⃣ Shipping class
    $shippingClass = $this->resolveShippingClass($totalUnits);

    // 4️⃣ Final price
    $shippingPrice = $basePrice * $shippingClass->load_factor;

    return response()->json([
        'base_price' => $basePrice,
        'total_units' => $totalUnits,
        'shipping_class' => $shippingClass->name,
        'load_factor' => $shippingClass->load_factor,
        'shipping_price' => $shippingPrice
    ]);
}


    // use App\Models\LagosLocation; // ✅ import the model


    public function getSavedAddress(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Fetch the default address (instead of latest)
        $address = Address::where('user_id', $user->id)->default()->first();

        if ($address) {
            return response()->json([
                'address' => [
                    'id' => $address->id,
                    'full_name' => $address->full_name,
                    'phone' => $address->phone,
                    'state_id' => $address->state_id,
                    'state' => $address->state->name, // Add state name
                    'lga' => $address->lga,
                    'street' => $address->street,
                    'description' => $address->description,
                    'additional_info' => $address->additional_info,
                ]
            ]);
        }

        return response()->json(null);
    }

        private function calculateTotalUnits(array $items): int
    {
        $totalUnits = 0;

        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $totalUnits += $product->shipping_unit * $item['quantity'];
        }

        return $totalUnits;
    }

    private function resolveShippingClass(int $totalUnits): ShippingClass
{
    // Normal range match
    $class = ShippingClass::where('min_units', '<=', $totalUnits)
        ->where('max_units', '>=', $totalUnits)
        ->first();

    // If above highest max → extra_heavy
    if (!$class) {
        $class = ShippingClass::orderBy('max_units', 'desc')->first();
    }

    return $class;
}

      public function saveAddress(Request $request)
{
    try {
        $validated = $request->validate([
            'customer.full_name' => 'required|string|max:255',
            'customer.phone' => 'required|string|max:20',
            'address.state' => 'required|string',
            'address.lga' => 'required|string',
            'address.street' => 'required|string',
            'address.description' => 'required|string',
        ]);

        $state = State::where('name', $request->address['state'])->firstOrFail();

        // Check if user already has a default address
        $hasDefaultAddress = Address::where('user_id', auth()->id())
                                  ->where('is_default', true)
                                  ->exists();

        $addressData = [
            'user_id' => auth()->id(),
            'full_name' => $request->customer['full_name'],
            'phone' => $request->customer['phone'],
            'state_id' => $request->address['state_id'] ?? $state->id,
            'lga' => $request->address['lga'],
            'street' => $request->address['street'],
            'description' => $request->address['description'],
            'additional_info' => $request->address['additional_info'] ?? null,
        ];

        // If user doesn't have a default address, make this one the default
        if (!$hasDefaultAddress) {
            $addressData['is_default'] = true;
        }

        $address = Address::create($addressData);

        return response()->json([
            'success' => true,
            'address' => [
                'id' => $address->id,
                'full_name' => $address->full_name,
                'phone' => $address->phone,
                'state' => $address->state->name,
                'lga' => $address->lga,
                'street' => $address->street,
                'description' => $address->description,
                'additional_info' => $address->additional_info,
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('Error saving address: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to save address: ' . $e->getMessage()
        ], 500);
    }
}

//    public function getLastAddress(Request $request)
// {
//     try {
//         $address = Address::where('user_id', auth()->id())
//                           ->latest()
//                           ->first();

//         if (!$address) {
//             return response()->json(['address' => null]);
//         }

//         // Return the relevant fields
//         return response()->json([
//             'address' => [
//                 'id' => $address->id,
//                 'full_name' => $address->full_name,
//                 'phone' => $address->phone,
//                 'state' => $address->state_id ? $address->state->name : '', // if you have relationship
//                 'lga' => $address->lga,
//                 'street' => $address->street,
//                 'landmark' => $address->landmark,
//                 'additional_info' => $address->additional_info,
//             ]
//         ]);
//     } catch (\Throwable $e) {
//         report($e);
//         return response()->json([
//             'address' => null,
//             'error' => 'Failed to get address',
//             'message' => $e->getMessage()
//         ]);
//     }
// }


    


}



/**
 * Process checkout and create order
 */
//    public function placeOrder(Request $request)
//     {
//         DB::transaction(function () use ($request) {

//             $subtotal = Cart::subtotalFloat();
//             $shipping = $request->shipping_price;
//             $tax = round($subtotal * 0.07);
//             $total = $subtotal + $shipping + $tax;

//             $order = Order::create([
//                 'user_id' => auth()->id(),
//                 'subtotal' => $subtotal,
//                 'shipping_price' => $shipping,
//                 'tax' => $tax,
//                 'total' => $total,
//                 'shipping_zone' => $request->shipping_zone,
//                 'shipping_class' => $request->shipping_class,
//                 'shipping_distance_km' => $request->distance_km,
//             ]);

//             foreach (Cart::content() as $item) {
//                 $order->items()->create([
//                     'product_id' => $item->id,
//                     'product_name' => $item->name,
//                     'price' => $item->price,
//                     'quantity' => $item->qty,
//                     'subtotal' => $item->price * $item->qty,
//                 ]);
//             }

//             $order->address()->create([
//                 'full_name' => $request->full_name,
//                 'phone' => $request->phone,
//                 'street' => $request->street,
//                 'lga' => $request->lga,
//                 'state' => $request->state,
//                 'landmark' => $request->landmark,
//             ]);

//             Cart::destroy();
//         });

//         return redirect()->route('payment.start');
//     }
// }
