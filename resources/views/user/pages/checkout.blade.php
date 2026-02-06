@extends('user.layouts.app', ['title' => 'Checkout'])
@section('content')
    <!-- Page Header -->
    <section class="text-black dark:text-white py-12 md:py-16">
        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Checkout</h1>
            <p class="text-light-black text-base sm:text-lg">Complete your order and choose payment method</p>
        </div>
    </section>

    <!-- Checkout Content -->
    <div class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl">
        <div x-data="checkoutForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Customer & Shipping Info -->
            <div class="lg:col-span-2">
            <!-- Address Section -->
    <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-6 sm:p-8 mb-8">
        <template x-if="savedAddress">
                        <div class="bg-category-light dark:bg-category-dark">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Delivery Address</h3>
                            <p class="text-gray-800 dark:text-gray-200 mb-1"><strong>Name:</strong> <span x-text="savedAddress.full_name"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-1"><strong>Phone:</strong> <span x-text="savedAddress.phone"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-1"><strong>Address:</strong> <span x-text="savedAddress.street"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-1"><span x-text="savedAddress.lga"></span>, <span x-text="savedAddress.state"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-1" x-show="savedAddress.landmark"><strong>Landmark:</strong> <span x-text="savedAddress.landmark"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-4" x-show="savedAddress.description"><strong>Description:</strong> <span x-text="savedAddress.description"></span></p>
                            <p class="text-gray-800 dark:text-gray-200 mb-4" x-show="savedAddress.additional_info"><strong>Additional Info:</strong> <span x-text="savedAddress.additional_info"></span></p>
                            
                            <div class="flex gap-3">
                                <button 
                                    @click="editAddress" 
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium text-sm">
                                    Edit Address
                                </button>
                            </div>
                        </div>
                    </template>

        <!-- ADDRESS FORM -->
        <template x-if="!savedAddress">
            <form @submit.prevent="saveAddress" class="space-y-4">

                <!-- Full Name -->
                <div>
                    <label class="block font-medium mb-1">Full Name</label>
                    <input
                        type="text"
                        x-model="full_name"
                        required
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                </div>

                <!-- Phone -->
                <div>
                    <label class="block font-medium mb-1">Phone Number</label>
                    <input
                        type="tel"
                        x-model="phone"
                        required 
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                </div>

                <!-- Street -->
                <div>
                    <label class="block font-medium mb-1">Street / House No.</label>
                    <input
                        type="text"
                        x-model="street"
                        @blur="fetchShippingPrice()"
                        required
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                </div>

                <!-- State -->
                <div>
                    <label class="block font-medium mb-1">State</label>
                    <select
                        x-model="selectedState"
                        @change="updateLgas()"
                        required
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                        <option value="">Select State</option>
                        <template x-for="state in states" :key="state">
                            <option x-text="state" :value="state"></option>
                        </template>
                    </select>
                </div>

                <!-- LGA -->
                <div x-show="lgas.length">
                    <label class="block font-medium mb-1">City / L.G.A</label>
                    <select
                        x-model="selectedLga"
                        @change="fetchShippingPrice()"
                        required
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                        <option value="">Select City / L.G.A</option>
                        <template x-for="lga in lgas" :key="lga.id">
                            <option :value="lga.name" x-text="lga.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Landmark -->
                <div>
                    <label class="block font-medium mb-1">Landmark (Optional)</label>
                    <input
                        type="text" 
                        x-model="landmark" 
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    >
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-medium mb-1">Full Description of Location</label>
                    <textarea
                        x-model="description"
                        rows="3"
                        required :value="description" x-text="description"
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    ></textarea>
                </div>

                <!-- Additional Info -->
                <div>
                    <label class="block font-medium mb-1">Additional Information (Optional)</label>
                    <textarea
                        x-model="additional_info"
                        rows="2" :value="additional_info" x-text="additional_info"
                        class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-800"
                    ></textarea>
                </div>

                <!-- Save -->
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-bold"
                >
                    Save Address
                </button>
            </form>
        </template>
    </div>
        <!-- Payment Method --> 
        <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-6"> 
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Payment Method</h2> 
            <div class="space-y-3"> 
                <!-- Cash on Delivery (Lagos only) --> 
                <label class="flex items-center gap-2"> 
                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" 
                        :disabled="selectedState.toLowerCase() !== 'lagos'" 
                        class="form-radio h-4 w-4 text-brand-600"> 
                    <span class="text-gray-900 dark:text-white text-sm" 
                        :class="selectedState.toLowerCase() !== 'lagos' ? 'opacity-50' : ''">
                        Payment on Delivery (For Residents in Lagos only)
                    </span> 
                </label> 
                
                <!-- Paystack (all locations) --> 
                <label class="flex items-center gap-2"> 
                    <input type="radio" name="payment_method" value="paystack" x-model="paymentMethod" 
                        class="form-radio h-4 w-4 text-brand-600"> 
                    <span class="text-gray-900 dark:text-white text-sm">
                        Pay with Paystack
                    </span> 
                </label> 
            </div> 
            
            <p x-show="selectedState.toLowerCase() !== 'lagos'" 
            class="text-red-500 text-xs mt-1"> 
                COD is only available for Lagos residents. 
            </p> 
            
            <p class="text-red-500 text-xs mt-1" 
               x-data="{ hasPreorder: false }"
               x-init="
               (async () => {
                   const cartItems = Alpine.store('cart').items;
                   for (const item of cartItems) {
                       const response = await fetch(`/api/product/${item.id}`);
                       const product = await response.json();
                       if (product.stock_status === 'preorder') {
                           hasPreorder = true;
                           break;
                       }
                   }
               })()
               "
               x-show="hasPreorder">
                COD is not available for preorder items. Please select Paystack as your payment method.
            </p> 
        </div> 
    </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-4 sm:p-6 sticky top-20 space-y-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Order Summary</h2>

                    <template x-for="item in $store.cart.items" :key="item.id">
                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                            <span x-text="item.name + ' x ' + item.quantity"></span>
                            <span x-text="'₦' + (item.price * item.quantity).toLocaleString('en-NG')"></span>
                        </div>
                    </template>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                            <span>Subtotal</span>
                            <span x-text="'₦' + cartSubtotal.toLocaleString('en-NG')"></span>
                        </div>

                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                            <span>Shipping</span>
                            <span x-text="shippingPrice ? '₦' + shippingPrice.toLocaleString('en-NG') : '₦0'"></span>
                        </div>

                        <div class="flex items-center justify-between text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                            <span>Total</span>
                            <span x-text="'₦' + grandTotal.toLocaleString('en-NG')"></span>
                        </div>
                        </div>

                        <template x-if="$store.cart.items.length > 0">
                            <button @click="submitOrder()"  
                                class="w-full px-6 py-3 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-bold transition-colors text-sm sm:text-base">
                                Place Order
                            </button>
                        </template>

        
                
                </div>
                    </div>
                </div>
            </div>  
        
        </div>
    </div>
@endsection