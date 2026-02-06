@props(['orders' => collect()])

<x-common.component-card title="Recent Orders">
    <x-tables.basic-tables.basic-tables-three 
        title="Recent Orders"
        :columns="['Order #','Customer','Date','Total','Status']"
        >
        @forelse($orders as $order)
            <tr>
                <!-- Order # -->
                <td class="px-4 py-4 whitespace-nowrap border-l border-r border-gray-200 dark:border-gray-700">
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
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                    No recent orders found.
                </td>
            </tr>
        @endforelse

         <x-slot name="actions" class="mt-4 flex justify-end"> 
            <a href="{{ route('orders.index') }}" 
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-500 to-brand-700 rounded-md hover:from-brand-600 hover:to-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                View All Orders
            </a>
          </x-slot>
    </x-tables.basic-tables.basic-tables-three>
    
    
  
</x-common.component-card>