@extends('admin.layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Orders" />
<div class="space-y-6">
    <x-common.component-card title="Latest Orders">
        {{-- ======================= --}}
        {{-- DESKTOP TITLE + TABLE (lg+) --}}
        {{-- ======================= --}}
        <div class="hidden lg:block">
            <x-tables.basic-tables.basic-tables-three 
                title="Latest Orders"
                :columns="['Order #','Customer','Date','Total','Status','Payment Method', 'Details ']"
                >
                @forelse($orders as $order)
                    <tr>
                        <!-- Order # -->
                        <td class="px-4 py-4 whitespace-nowrap border-r border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                KMW-ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <!-- Customer -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            <div>
                                <div class="font-medium">{{ $order->user ? $order->user->name : 'Guest' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->user ? $order->user->email : 'N/A' }}</div>
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </td>

                        <!-- Total -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            ₦{{ number_format($order->total, 2) }}
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            <span class="badge 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($order->status == 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <!-- Payment Method -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                            @if($order->transaction)
                                {{ ucfirst($order->transaction->payment_method) }}
                            @else
                                N/A
                            @endif
                        </td>

                        <!-- Details or Actions -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center border-r border-gray-200 dark:border-gray-700">
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-500 to-brand-700 rounded-md hover:from-brand-600 hover:to-brand-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse

                <x-slot name="pagination">
                    {{ $orders->links() }}
                </x-slot>
            </x-tables.basic-tables.basic-tables-three>
        </div>
        
        {{-- ======================= --}}
        {{-- MOBILE CARDS (< lg) --}}
        {{-- ======================= --}}
        <div class="lg:hidden space-y-4">
            @forelse($orders as $order)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-category-white dark:bg-category-dark p-4 shadow-sm hover:shadow-md transition">
                    
                    {{-- Header --}}
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                KMW-ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                {{ $order->user ? $order->user->name : 'Guest' }}
                            </p>
                        </div>
                        
                        <span class="text-xs px-2 py-1 rounded-full
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($order->status == 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    {{-- Details --}}
                    <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Date</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $order->created_at->format('M d, Y') }}</span></div>
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</span>
                            <span class="text-gray-900 dark:text-white font-medium">₦{{ number_format($order->total, 2) }}</span></div>
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Customer</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $order->user ? $order->user->name : 'Guest' }}</span></div>
                        <div><span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Payment</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $order->transaction ? ucfirst($order->transaction->payment_method) : 'N/A' }}</span></div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 grid grid-cols-1 gap-3">
                        <a href="{{ route('orders.show', $order->id) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-500 to-brand-700 rounded-md hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-6">No orders found.</p>
            @endforelse

            {{-- Mobile Pagination --}}
            @if ($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </x-common.component-card>
</div>
@endsection