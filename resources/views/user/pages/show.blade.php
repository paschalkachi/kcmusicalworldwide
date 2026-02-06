@extends('user.layouts.app', ['title' => $product->name])

@section('content')

    @php
        // Ensure gallery images are always an array
        $galleryImages = is_string($product->images) ? json_decode($product->images, true) : $product->images ?? [];

        $galleryImages = is_array($galleryImages) ? $galleryImages : [];
    @endphp

    <!-- Breadcrumb -->
    <section class="px-4 md:px-6 py-6 mx-auto max-w-7xl">
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}">Shop</a>
            <span>/</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</span>
        </div>
    </section>

    <!-- Product Details -->
    <section class="px-4 md:px-6 py-8 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            <!-- Product Images -->
            <div class="relative">

                <!-- Swiper Main -->
                <div class="swiper productSwiper mb-6">
                    <div class="swiper-wrapper">

                        <!-- Main Image -->
                        <div class="swiper-slide">
                            <div
                                class="zoom-container overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 aspect-square">
                                <img src="{{ asset('uploads/products/' . $product->image) }}"
                                    class="zoom-img w-full h-full object-cover" alt="{{ $product->name }}">
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        @foreach ($galleryImages as $img)
                            <div class="swiper-slide">
                                <div
                                    class="zoom-container overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 aspect-square">
                                    <img src="{{ asset('uploads/products/' . $img) }}"
                                        class="zoom-img w-full h-full object-cover" alt="{{ $product->name }}">
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <!-- Thumbnail Swiper -->
                <div class="swiper thumbSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('uploads/products/' . $product->image) }}"
                                class="w-20 h-20 object-cover rounded-lg cursor-pointer mr-0">
                        </div>

                        @foreach ($galleryImages as $img)
                            <div class="swiper-slide">
                                <img src="{{ asset('uploads/products/' . $img) }}"
                                    class="w-20 h-20 object-cover rounded-lg cursor-pointer">
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <!-- Product Info -->
            <div>
                <!-- Brand / Category -->
                <div class="flex gap-3 mb-4">
                    @if ($product->brand)
                        <span class="px-3 py-1 bg-brand-100 dark:bg-brand-900 text-xs rounded-full">
                            {{ $product->brand->name }}
                        </span>
                    @endif
                    @if ($product->category)
                        <span class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-xs rounded-full">
                            {{ $product->category->name }}
                        </span>
                    @endif
                </div>

                <!-- Product Name & Rating -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $product->name }}</h1>

                <!-- Ratings -->
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-gray-600 dark:text-gray-400">(128 reviews)</span>
                </div>

                <!-- Short Description -->
                @if ($product->short_description)
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-6 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                @endif
            </div>

            <!-- Pricing & Purchase -->
            <div class="space-y-6 py-6 border-t border-b border-gray-200 dark:border-gray-700">
                <!-- Price -->
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Price</p>
                    <div class="flex items-center gap-3">
                        @if ($product->sale_price)
                            <span class="text-3xl font-bold text-brand-600 dark:text-brand-400">
                                ₦{{ number_format($product->sale_price, 0) }}
                            </span>
                            <span class="text-lg text-gray-500 dark:text-gray-400 line-through">
                                ₦{{ number_format($product->regular_price, 0) }}
                            </span>
                        @else
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                ₦{{ number_format($product->regular_price, 0) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">Availability</p>
                    @if ($product->stock_status === 'instock')
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="font-semibold text-green-600 dark:text-green-400">In Stock
                                ({{ $product->getAvailableQuantityAttribute() }} available)</span>
                        </div>
                    @elseif ($product->stock_status === 'preorder')
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <span class="font-semibold text-yellow-600 dark:text-yellow-400">Pre-order
                                ({{ $product->getAvailableQuantityAttribute() }} available)</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="font-semibold text-red-600 dark:text-red-400">Out of Stock</span>
                        </div>
                    @endif
                </div>

                <!-- SKU -->
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase font-semibold">SKU</p>
                    <p class="font-mono text-gray-700 dark:text-gray-300">{{ $product->SKU ?? 'N/A' }}</p>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="space-y-4 pt-4">
                    <div x-data="{ quantity: 1 }" class="flex gap-4">
                        <!-- Quantity Selector -->
                        <div class="flex items-center border border-gray-300 dark:border-gray-700 rounded-lg">
                            <button @click="quantity = Math.max(1, quantity - 1)"
                                class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                −
                            </button>
                            <span
                                class="px-6 py-3 border-l border-r border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white font-semibold"
                                x-text="quantity">
                            </span>
                            <button
                                @click="quantity = Math.min({{ $product->getAvailableQuantityAttribute() ?? 1000 }}, quantity + 1)"
                                class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                +
                            </button>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            @click.prevent="
            if ('{{ $product->stock_status }}' === 'outofstock' || {{ $product->getAvailableQuantityAttribute() }} <= 0) {
                alert('Sorry, this product is out of stock or reserved by other customers.');
            } else {
                const added = $store.cart.addItem({
                    id: {{ $product->id }},
                    name: '{{ $product->name }}',
                    price: {{ $product->sale_price ?? $product->regular_price }},
                    image: '{{ $product->image }}',
                    quantity: quantity,
                    quantity_limit: {{ $product->getAvailableQuantityAttribute() }}
                });                
                if(added) {
                    alert('Product added to cart successfully!');
                }
            }
        "
                            class="flex-1 py-3 px-6 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-bold transition-colors">
                            Add to Cart
                        </button>
                    </div>


                    <button
                        class="w-full py-3 px-6 border-2 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg font-bold hover:border-brand-600 hover:text-brand-600 dark:hover:border-brand-400 dark:hover:text-brand-400 transition-colors">
                        ♡ Add to Wishlist
                    </button>
                </div>

                <!-- Share -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Share:</span>
                    <button
                        class="p-2 text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </button>
                    <button
                        class="p-2 text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 002.856-3.51 10 10 0 01-2.856.97 4.96 4.96 0 002.165-2" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Description Tabs -->
    <section class="px-4 md:px-6 py-8 md:py-12 mx-auto max-w-7xl border-t border-gray-200 dark:border-gray-700">
        <div x-data="{ activeTab: 'description' }" class="space-y-8">
            <!-- Tab Buttons -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 gap-8">
                <button @click="activeTab = 'description'"
                    :class="activeTab === 'description' ? 'border-b-2 border-brand-600 text-brand-600 dark:text-brand-400' :
                        'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="pb-4 font-semibold transition-colors">
                    Description
                </button>
                <button @click="activeTab = 'specifications'"
                    :class="activeTab === 'specifications' ? 'border-b-2 border-brand-600 text-brand-600 dark:text-brand-400' :
                        'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="pb-4 font-semibold transition-colors">
                    Specifications
                </button>
                <button @click="activeTab = 'reviews'"
                    :class="activeTab === 'reviews' ? 'border-b-2 border-brand-600 text-brand-600 dark:text-brand-400' :
                        'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="pb-4 font-semibold transition-colors">
                    Reviews
                </button>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'description'" x-transition class="prose dark:prose-invert max-w-none">
                @if ($product->description)
                    <div class="text-gray-700 dark:text-gray-300 space-y-4 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">No detailed description available for this product.</p>
                @endif
            </div>

            <!-- Specifications Tab -->
            <div x-show="activeTab === 'specifications'" x-transition>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 uppercase font-semibold mb-2">SKU</p>
                            <p class="font-mono text-gray-900 dark:text-white">{{ $product->SKU ?? 'N/A' }}</p>
                        </div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 uppercase font-semibold mb-2">Stock Status
                            </p>
                            <p class="text-gray-900 dark:text-white">
                                {{ ucfirst(str_replace('-', ' ', $product->stock_status)) }}</p>
                        </div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 uppercase font-semibold mb-2">Quantity
                                Available</p>
                            <p class="text-gray-900 dark:text-white">{{ $product->quantity }} units</p>
                        </div>
                        @if ($product->category)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 uppercase font-semibold mb-2">Category
                                </p>
                                <p class="text-gray-900 dark:text-white">{{ $product->category->name }}</p>
                            </div>
                        @endif
                        @if ($product->brand)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 uppercase font-semibold mb-2">Brand</p>
                                <p class="text-gray-900 dark:text-white">{{ $product->brand->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-transition>
                <div class="space-y-6">
                    <div class="flex items-center gap-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-white">4.5</p>
                            <div class="flex text-yellow-400 mt-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Based on 128 reviews</p>
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-12 text-sm text-gray-600 dark:text-gray-400">{{ $i }}
                                        star</span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 max-w-xs">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ (6 - $i) * 20 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="w-12 text-sm text-gray-600 dark:text-gray-400">{{ (6 - $i) * 20 }}%</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <button
                        class="px-8 py-3 bg-brand-600 hover:bg-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700 text-white rounded-lg font-bold transition-colors">
                        Write a Review
                    </button>

                    <div class="space-y-6 mt-8">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">John Doe</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Verified Purchase • 2 weeks ago
                                        </p>
                                    </div>
                                    <div class="flex text-yellow-400 text-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">Excellent product! Very satisfied with the
                                    quality and it arrived in perfect condition. Highly recommended.</p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <section class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl border-t border-gray-200 dark:border-gray-700">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Related Products</h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg">You might also like these items</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts->take(4) as $relatedProduct)
                    @include('user.components.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const thumbSwiper = new Swiper('.thumbSwiper', {
                spaceBetween: 10,
                slidesPerView: 6,
                freeMode: true,
                watchSlidesProgress: true,
            });

            new Swiper('.productSwiper', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
            });

        });
    </script>
@endpush
