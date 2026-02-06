@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Order Details" />

<div class="mb-6">
    <a href="{{ route('orders.index') }}"
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
        ← Back to Orders
    </a>
</div>

<div class="space-y-6">
    {{-- Order Summary Card --}}
    <x-common.component-card title="Order Summary">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Order Info --}}
            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Order Information</h4>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Order #:</span> KMW-ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Status:</span> 
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 
                        @elseif($order->status == 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 
                        @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 
                        @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Date:</span> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Payment Method:</span> {{ $order->transaction ? ucfirst($order->transaction->payment_method) : 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Transaction Reference:</span> {{ $order->transaction ? $order->transaction->reference : 'N/A' }}</p>
            </div>

            {{-- Customer Info --}}
            <div class="space-y-2">
                <h4 class="font-semibold text-gray-800 dark:text-white/90">Customer Information</h4>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Name:</span> {{ $order->user ? $order->user->name : 'Guest' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Email:</span> {{ $order->user ? $order->user->email : 'N/A' }}</p>
                @if($order->state)
                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">State:</span> {{ $order->state->name }}</p>
                @endif
                @if($order->lagosLocation)
                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-gray-900 dark:text-white">Location:</span> {{ $order->lagosLocation->name }}</p>
                @endif
            </div>
        </div>
    </x-common.component-card>

    {{-- Update Order Status Card --}}
    <x-common.component-card title="Update Order Status">
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-brand-25 dark:bg-gray-800 rounded-lg p-6 border border-brand-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Current Status</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Current order status is:
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($order->status == 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-grow">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Update Status
                            </label>
                            
                            <select id="status" name="status" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-base dark:bg-gray-800 dark:text-white dark:border-gray-700 border-2 border-gray-300 dark:border-gray-700 py-3 px-4 transition-all duration-200 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-700 text-white font-semibold rounded-lg hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-500 focus:ring-opacity-50 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-common.component-card>

    {{-- Order Items Card --}}
    <x-common.component-card title="Items in this Order">
        <x-tables.basic-tables.basic-tables-three 
            title="Order Items"
            :columns="['Product','Price','Quantity','Subtotal']"
            >
            @forelse($order->items as $item)
                <tr>
                    <!-- Product -->
                    <td class="px-4 py-4 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            @if($item->product && $item->product->skus && $item->product->skus->first())
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ $item->product->skus->first()->image ? asset('uploads/products/'.$item->product->skus->first()->image) : asset('images/product-placeholder.jpg') }}" 
                                         alt="{{ $item->product_name }}">
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->product_name }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Price -->
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                        ₦{{ number_format($item->price, 2) }}
                    </td>

                    <!-- Quantity -->
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                        {{ $item->quantity }}
                    </td>

                    <!-- Subtotal -->
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                        ₦{{ number_format($item->subtotal, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        No items found for this order.
                    </td>
                </tr>
            @endforelse
        </x-tables.basic-tables.basic-tables-three>
    </x-common.component-card>

    {{-- Payment & Totals --}}
    <x-common.component-card class="mx-4" title="Payment & Totals">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Subtotal
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            ₦{{ number_format($order->subtotal, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Shipping
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            ₦{{ number_format($order->shipping_price, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Tax
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            ₦{{ number_format($order->tax, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Total Units
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->total_units }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Shipping Class
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ optional($order->shippingClass)->name ?? '—' }}
                        </td>
                    </tr>
                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                        <td class="px-4 py-4 whitespace-nowrap text-base font-bold text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                            Total Amount
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-base font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-common.component-card>

</div>
@endsection