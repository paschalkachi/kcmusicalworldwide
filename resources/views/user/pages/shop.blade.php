@extends('user.layouts.app', ['title' => 'Shop'])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Shop Our Products</h1>
        <p class="text-brand-100 text-lg">Browse our complete collection of musical instruments and accessories</p>
    </div>
</section>

<!-- Main Content -->
<div class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <!-- Mobile Filter Toggle -->
            <button x-data="{ open: false }" @click="open = !open" class="lg:hidden w-full mb-6 px-4 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg transition-colors flex items-center justify-between">
                <span>Filters</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div x-data="{ open: false }" class="hidden lg:block" :class="open && 'block'">
                <div class="sticky top-20 space-y-6">
                    <!-- Search Filter -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Search</h3>
                        <input type="text" placeholder="Search products..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Categories</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">All Categories</span>
                            </label>
                            @forelse ($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">{{ $category->name }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No categories available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Brands</h3>
                        <div class="space-y-3">
                            @forelse ($brands as $brand)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500" value="{{ $brand->id }}">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">{{ $brand->name }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No brands available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Price Range</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">₦0 - ₦50,000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">₦50,000 - ₦100,000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">₦100,000 - ₦500,000</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">₦500,000+</span>
                            </label>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Stock Status</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">In Stock</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-brand-600 rounded focus:ring-2 focus:ring-brand-500">
                                <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">On Sale</span>
                            </label>
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <button class="w-full px-6 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white font-bold rounded-lg transition-colors">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $products->count() }}</span> products found
                </div>
                <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm sm:text-base">
                    <option>Sort by: Newest</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Best Sellers</option>
                    <option>Most Reviewed</option>
                </select>
            </div>

            <!-- Products -->
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        @include('user.components.product-card')
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex flex-wrap items-center justify-center gap-2 px-2">
                    <button class="px-3 sm:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm sm:text-base">
                        &larr; Previous
                    </button>
                    <button class="px-3 sm:px-4 py-2 rounded-lg bg-brand-500 text-white font-semibold text-sm sm:text-base">1</button>
                    <button class="px-3 sm:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm sm:text-base">2</button>
                    <button class="px-3 sm:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm sm:text-base">3</button>
                    <button class="px-3 sm:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm sm:text-base">
                        Next &rarr;
                    </button>
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">No products match your filters.</p>
                    <p class="text-gray-500 dark:text-gray-500 mt-2">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- CTA Section -->
<section class="bg-brand-500 text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">Can't find what you're looking for?</h2>
        <p class="text-brand-100 text-base sm:text-lg mb-8 px-2">Contact our expert team for personalized recommendations</p>
        <a href="#contact" class="inline-block px-6 sm:px-8 py-3 bg-white text-brand-500 rounded-lg font-bold hover:bg-gray-100 transition-colors text-sm sm:text-base">
            Contact Us
        </a>
    </div>
</section>

@endsection
