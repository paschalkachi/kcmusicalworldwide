@extends('user.layouts.app', ['title' => $title])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $title }}</h1>
        <p class="text-light-black text-lg">View and manage your order history</p>
    </div>
</section>

<!-- Orders Content -->
<div class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Mobile Menu Button -->
        <div class="lg:hidden mb-6">
            <button id="mobile-menu-button" class="w-full px-4 py-3 bg-brand-500 text-white rounded-lg font-medium flex items-center justify-between">
                <span>Menu</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div id="sidebar-nav" class="lg:col-span-1 hidden lg:block absolute lg:relative z-10 w-64 lg:w-auto bg-white dark:bg-gray-800 lg:bg-transparent p-6 rounded-xl shadow-md lg:shadow-none mt-2 lg:mt-0 ml-4 lg:ml-0">
            <div class="sticky top-20 space-y-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                <a href="{{ route('profile') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Account Settings
                </a>
                <a href="{{ route('user.orders') }}" class="block px-4 py-3 rounded-lg bg-brand-600 text-white font-medium transition-colors">
                    My Orders
                </a>
                <a href="{{ route('user.addresses') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Addresses
                </a>
                <a href="{{ route('user.wishlist') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Wishlist
                </a>
                <a href="{{ route('user.reviews') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Reviews
                </a>
                <hr class="my-3 border-gray-200 dark:border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg font-medium transition-colors text-left">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Order History</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Order ID</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Total</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">₦{{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('orders.confirmation', $order) }}" class="text-brand-600 dark:text-brand-400 hover:underline text-sm font-medium">View Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-600 dark:text-gray-400">
                                        You haven't placed any orders yet. <a href="{{ route('shop') }}" class="text-brand-600 dark:text-brand-400 hover:underline">Start shopping</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebarNav = document.getElementById('sidebar-nav');
    
    if(mobileMenuButton && sidebarNav) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.preventDefault();
            sidebarNav.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebarNav.contains(event.target);
            const isClickOnMenuButton = mobileMenuButton.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickOnMenuButton && !sidebarNav.classList.contains('hidden')) {
                if(window.innerWidth < 1024) { // lg breakpoint
                    sidebarNav.classList.add('hidden');
                }
            }
        });
    }
});
</script>

@endsection