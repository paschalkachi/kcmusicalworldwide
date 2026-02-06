@extends('user.layouts.app', ['title' => 'Order Confirmation'])
@section('content')

    <!-- Page Header -->
    <section class="text-gray-800 dark:text-white py-12 md:py-16">
        <div class="px-4 md:px-6 mx-auto max-w-7xl text-center">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 {{ $order->transaction->payment_method === 'paystack' ? 'bg-green-500' : 'bg-orange-500' }} rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl sm:text-4xl font-bold mb-2 text-gray-900 dark:text-white">
                {{ $order->transaction->payment_method === 'paystack' ? 'Payment Successful' : 'Order Placed Successfully' }}
            </h1>

            <p class="text-gray-600 dark:text-gray-300">
                {{ $order->transaction->payment_method === 'paystack'
                    ? 'Your payment has been confirmed'
                    : 'Please pay upon delivery' }}
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="px-4 md:px-6 py-12 mx-auto max-w-6xl">

        {{-- ================= ORDER SUMMARY ================= --}}
        <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6 mb-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Order Number</p>
                    <p class="font-bold text-lg text-gray-800 dark:text-white">#{{ sprintf('%05d', $order->id) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Order Date</p>
                    <p class="font-bold text-lg text-gray-800 dark:text-white">{{ $order->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="font-bold capitalize {{ $order->status === 'paid' ? 'text-green-500' : 'text-orange-500' }} dark:text-white">
                        {{ $order->status }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment</p>
                    <p class="font-bold uppercase text-gray-800 dark:text-white">
                        {{ $order->transaction->payment_method === 'cod' ? 'Cash on Delivery' : 'Paystack' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ================= LEFT COLUMN ================= --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- ================= ORDER ITEMS ================= --}}
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Order Items</h2>

                    @forelse($order->items as $item)
                        <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 py-4 last:border-0">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold text-gray-800 dark:text-white">₦{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No items in this order</p>
                    @endforelse
                </div>

                {{-- ================= INVOICE (ALWAYS) ================= --}}
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6" id="invoice">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Invoice</h2>
                        <a href="{{ route('invoice.download', $order) }}" class="btn-primary bg-brand-500 hover:bg-brand-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                            Download Invoice
                        </a>

                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Invoice #: INV-{{ sprintf('%05d', $order->id) }}
                    </p>

                    <!-- Order Totals -->
                    <div class="space-y-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                            <span>Subtotal</span>
                            <span>₦{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>Shipping</span>
                            <span>₦{{ number_format($order->shipping_price, 2) }}</span>
                        </div>
                        @if($order->tax > 0)
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                            <span>Tax</span>
                            <span>₦{{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 dark:border-gray-700 pt-2 mt-2 text-gray-800 dark:text-white">
                            <span>Total</span>
                            <span class="text-brand-500">₦{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    @if($order->transaction->payment_method === 'cod')
                        <div class="mt-4 bg-orange-100 dark:bg-orange-900/20 p-4 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                            Payment will be collected upon delivery.
                        </div>
                    @endif
                </div>

                {{-- ================= RECEIPT (PAYSTACK ONLY) ================= --}}
                @if($order->transaction->payment_method === 'paystack')
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6" id="receipt">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Payment Receipt</h2>
                        @if($order->transaction->payment_method === 'paystack')
                        <a href="{{ route('receipt.download', $order) }}" class="btn-primary bg-brand-500 hover:bg-brand-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                            Download Receipt
                        </a>
                        @endif
                    </div>

                    <div class="text-sm space-y-2 text-gray-600 dark:text-gray-300">
                        <p><strong>Payment Gateway:</strong> Paystack</p>
                        <p><strong>Reference:</strong> {{ $order->transaction?->reference ?? 'N/A' }}</p>
                        <p><strong>Amount Paid:</strong> ₦{{ number_format($order->total, 2) }}</p>
                        <p><strong>Paid On:</strong> {{ $order->transaction && $order->transaction->paid_at ? \Carbon\Carbon::parse($order->transaction->paid_at)->format('M d, Y g:i A') : 'N/A' }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ================= RIGHT COLUMN ================= --}}
            <div class="space-y-8">

                {{-- TOTAL --}}
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6">
                    <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Order Total</h3>

                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Total</span>
                        <span class="font-bold text-brand-500">
                            ₦{{ number_format($order->total, 2) }}
                        </span>
                    </div>
                </div>

                {{-- DELIVERY --}}
                <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-lg p-6">
                    <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Delivery Address</h3>
                    @if($order->address)
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $order->address->full_name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->address->street }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->address->lga }}, {{ $order->state->name }}</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $order->address->phone }}</p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Address not available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection