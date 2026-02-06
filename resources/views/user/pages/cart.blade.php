@extends('user.layouts.app', ['title' => 'Shopping Cart'])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Shopping Cart</h1>
        <p class="text-light-black text-base sm:text-lg">Review and manage your items</p>
    </div>
</section>

<!-- Cart Content -->
<div class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <!-- Alpine.js Component for Cart Item Controls -->
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('cartItemControls', () => ({
                        increaseQuantity(item) {
                            // Check if item has stock property and don't exceed it, default to 10 if not specified
                            const maxQuantity = item.stock || 10;
                            if (item.quantity < maxQuantity) {
                                $store.cart.updateQuantity(item.id, item.quantity + 1);
                            }
                        },
                        decreaseQuantity(item) {
                            if (item.quantity > 1) {
                                $store.cart.updateQuantity(item.id, item.quantity - 1);
                            }
                        }
                    }));
                });
            </script>
            
            <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md overflow-hidden" x-show="$store.cart.items.length > 0">
                <!-- Items Header -->
                <div class="bg-category-light hidden md:grid grid-cols-12 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="col-span-5">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                    <div class="col-span-1"></div>
                </div>

                <!-- Cart Items List -->
                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="grid grid-cols-12 gap-2 sm:gap-4 px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-200 dark:border-gray-700 items-center">
                        <!-- Product Image & Name -->
                        <div class="col-span-12 sm:col-span-5 flex gap-3 sm:gap-4">
                            <div class="w-16 sm:w-20 h-16 sm:h-20 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                <img :src="item.image ? '/uploads/products/' + item.image : '/images/placeholder.jpg'"
                                    :alt="item.name"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base truncate" x-text="item.name"></h3>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">SKU: <span x-text="'SKU-' + item.id"></span></p>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-span-6 sm:col-span-2 flex items-center justify-between sm:justify-center">
                            <span class="sm:hidden text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Price:</span>
                            <span class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base" x-text="'₦' + item.price.toLocaleString('en-NG')"></span>
                        </div>

                        <!-- Quantity -->
                        <div x-data="cartItemControls()" class="col-span-6 sm:col-span-2 flex items-center justify-between sm:justify-center gap-2">
                            
                            <span class="sm:hidden text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Qty:</span>
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                <!-- Decrease -->
                                <button 
                                    :disabled="item.quantity <= 1"
                                    class="px-2 sm:px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm"
                                    @click="decreaseQuantity(item)"
                                    :class="item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                >-</button>

                                <!-- Quantity display -->
                                <span 
                                    class="px-2 sm:px-3 py-1 border-l border-r border-gray-300 dark:text-white dark:border-gray-600 text-sm"
                                    x-text="item.quantity"
                                ></span>

                                <!-- Increase -->
                                <button 
                                    :disabled="item.quantity >= (item.stock || 10)"
                                    class="px-2 sm:px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm"
                                    @click="increaseQuantity(item)"
                                    :class="item.quantity >= (item.stock || 10) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                >+</button>                            
                            </div>                           
                        </div>

                        <!-- Remove Button -->
                        <div class="col-span-12 sm:col-span-1 flex justify-end sm:justify-end">
                            <button @click="$store.cart.removeItem(item.id)"
                                class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium text-xs sm:text-sm transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>
                </template>
                
                <!-- Clear Cart Button -->
                <div class="flex justify-between">
                    <div class="px-4 sm:px-6 py-4">
                        <button 
                            @click="window.location.href='{{ route('shop') }}'"
                            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium text-sm"
                            x-show="$store.cart.items.length > 0"
                        >
                           Back to Shop
                        </button>
                    </div>

                    <div class="px-4 sm:px-6 py-4">
                        <button 
                            @click="if(confirm('Are you sure you want to clear your entire cart?')) { window.clearCart(); }"
                            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium text-sm"
                            x-show="$store.cart.items.length > 0"
                        >
                            Clear Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty Cart -->
            <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-8 sm:p-12 text-center" x-show="$store.cart.items.length === 0">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m10 0l2-9m0 0h-2.586a1 1 0 00-.894.553l-1.473 2.945a1 1 0 01-.894.553H9.586a1 1 0 01-.894-.553l-1.473-2.945a1 1 0 00-.894-.553H5.4m2 9l1.5 1.5M7 13v8a2 2 0 002 2h6a2 2 0 002-2v-8"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg mb-6">Your cart is empty</p>
                <a href="{{ route('shop') }}"
                    class="inline-block px-6 sm:px-8 py-2 sm:py-3 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-bold transition-colors text-sm sm:text-base">
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-4 sm:p-6 sticky top-20">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>

                <div class="space-y-3 sm:space-y-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                        <span>Subtotal</span>
                        <span x-text="'₦' + $store.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0).toLocaleString('en-NG')"></span>
                    </div>
                    {{-- <div class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                        <span>Shipping</span>
                        <span class="italic text-sm">Calculated at checkout</span>
                    </div> --}}
                    {{-- <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                        <span>Tax</span>
                        <span x-text="'₦' + Math.round($store.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 0.07).toLocaleString('en-NG')"></span>
                    </div> --}}
                </div>

                {{-- <div class="flex items-center justify-between mb-6">
                    <span class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="text-xl sm:text-2xl font-bold text-brand-600 dark:text-brand-400"
                        x-text="'₦' + Math.round($store.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 1.07).toLocaleString('en-NG')"></span>
                </div> --}}

                <template x-if="$store.cart.items.length > 0">
                    <div class="w-full hover:cursor-pointer text-center px-6 py-2 sm:py-3 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-bold transition-colors text-sm sm:text-base">
                        <a href="{{ route('checkout') }}">
                            Proceed to Checkout
                        </a>
                    </div>
                </template>

                <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3 sm:mb-4 text-sm sm:text-base">Apply Coupon</h3>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" placeholder="Coupon code"
                            class="flex-1 px-3 sm:px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                        <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg transition-colors font-medium text-sm whitespace-nowrap">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Define clearCart function in the global scope for the button click handler
    window.clearCart = function() {
        // Clear the cart using the Alpine store
        Alpine.store('cart').clearCart();
        
        // Show a notification or reload the page to reflect changes
        location.reload();
    };
    
    // Define removeItem function in the global scope
    window.removeItem = function(itemId) {
        // Remove the specific item using the Alpine store
        Alpine.store('cart').removeItem(itemId);
        
        // Reload the page to reflect changes
        location.reload();
    };
</script>
@endpush
