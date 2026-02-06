@extends('user.layouts.app', ['title' => $title])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $title }}</h1>
        <p class="text-light-black text-lg">Your product reviews and feedback</p>
    </div>
</section>

<!-- Reviews Content -->
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
                <a href="{{ route('user.orders') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    My Orders
                </a>
                <a href="{{ route('user.addresses') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Addresses
                </a>
                <a href="{{ route('user.wishlist') }}" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors">
                    Wishlist
                </a>
                <a href="{{ route('user.reviews') }}" class="block px-4 py-3 rounded-lg bg-brand-600 text-white font-medium transition-colors">
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
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Your Reviews</h2>

                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No reviews yet</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Share your experience with products you've purchased.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('user.orders') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                            View Your Orders
                        </a>
                    </div>
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