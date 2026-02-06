<header class="sticky top-0 z-50 w-full bg-white dark:bg-black transition-all duration-300">
    <div class="px-4 md:px-6 py-4 mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <a href="/" class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                    {{-- <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2 1 1 0 100 2H3a1 1 0 100 2h8a3 3 0 100-6H4a1 1 0 100 2h4a1 1 0 000-2 2 2 0 00-2-2 6 6 0 00-6 6v10a2 2 0 002 2h10a2 2 0 002-2V7a1 1 0 100-2 1 1 0 000-2H4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="hidden sm:inline">KC Musical</span> --}}
                    <img class="dark:hidden w-40 h-auto" src="/images/logo/logo.png" alt="Logo" />
                    <img class="hidden dark:block w-40 h-auto" src="/images/logo/logo-dark.png" alt="Logo" />
                    {{-- <span class="hidden sm:inline">KC Musical</span> --}} 
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Home</a>
                <a href="{{ route('shop') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Shop</a>
                <a href="#products" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Products</a>
                <a href="#contact" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Contact</a>
            </nav>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Theme Toggle -->
                <button @click="$store.theme.toggle()"
                    class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <svg x-show="$store.theme.theme === 'light'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg x-show="$store.theme.theme === 'dark'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1h4a1 1 0 01.82.455l2.745 4.418a1 1 0 01-.164 1.248l-.604.604a1 1 0 001.414 1.414l.604-.604a1 1 0 011.248.164l4.418 2.745a1 1 0 01.455.82v4a1 1 0 11-2 0v-1a1 1 0 00-1-1H4a1 1 0 00-1 1v1a1 1 0 11-2 0v-4a1 1 0 01.455-.82l4.418-2.745a1 1 0 011.248.164l.604.604a1 1 0 001.414-1.414l-.604-.604a1 1 0 01-.164-1.248l2.745-4.418A1 1 0 0114 2h-4z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart') }}"
                    class="relative p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m10 0l2-9m0 0h-2.586a1 1 0 00-.894.553l-1.473 2.945a1 1 0 01-.894.553H9.586a1 1 0 01-.894-.553L7.219 5.553A1 1 0 016.325 5H3.739m9.272 0a2 2 0 00-3.468 0m15.338 5a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14a2 2 0 012 2z"></path>
                    </svg>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full"
                        x-text="$store.cart.count"
                        x-show="$store.cart.count > 0">
                        <span x-text="$store.cart.count"></span>
                    </span>
                </a>

                <!-- Account Menu (Desktop) -->
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        <!-- Show Admin Panel link if user has admin role -->
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors" title="Admin Panel">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8l8-8z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('profile') }}" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100  dark:hover:bg-gray-800 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-white bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 rounded-lg font-medium transition-colors">
                            Sign In
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="$store.cart.mobileMenuOpen = !$store.cart.mobileMenuOpen"
                    class="md:hidden p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="$store.cart.mobileMenuOpen" x-transition
            class="md:hidden pt-4 border-t border-gray-200 dark:border-gray-800 mt-4">
            <nav class="space-y-3">
                <a href="/" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Home</a>
                <a href="{{ route('shop') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Shop</a>
                <a href="#products" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Products</a>
                <a href="#contact" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Contact</a>
                <!-- Added Cart Link to Mobile Menu-->
                <a href="{{ route('cart') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors flex items-center gap-2">
                    <span>Cart</span>
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 text-xs font-bold text-white bg-brand-600 rounded-full py-0.5 px-1"
                        x-text="$store.cart.count"
                        x-show="$store.cart.count > 0">
                        <span x-text="$store.cart.count"></span>
                    </span>
                </a>
                @auth
                    <!-- Show Admin Panel link in mobile menu if user has admin role -->
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8l8-8z" clip-rule="evenodd" />
                            </svg>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                    <a href="{{ route('profile') }}" class="block text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">Profile</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium transition-colors">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-white bg-brand-600 hover:bg-brand-700 rounded-lg font-medium transition-colors text-center">
                        Sign In
                    </a>
                @endauth
            </nav>
        </div>
    </div>
</header>