@extends('user.layouts.app', ['title' => 'Home'])
@section('content')
    <!-- Hero / New Arrivals Section -->
    <section data-aos="fade-up" class="relative py-16 md:py-24 text-black dark:text-white relative overflow-hidden">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <div x-data="newArrivals()" class="relative overflow-hidden">

                <!-- Slide Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">

                    <!-- Left Text -->
                    <div class="transition-all duration-700" x-transition:enter="opacity-0"
                        x-transition:enter-start="opacity-0 translate-x-10"
                        x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="opacity-100"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-10">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">New Arrivals</h2>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4" x-text="currentProduct.name"></h1>
                        <p class="text-lg md:text-xl text-grey-600 dark:text-grey-400 mb-8 leading-relaxed"
                            x-text="currentProduct.short_description"></p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a :href="currentProduct.link"
                                class="px-8 py-3 bg-brand-500 text-white rounded-lg font-bold hover:bg-brand-400 transition-colors text-center">
                                Shop Now
                            </a>
                            <a href="#products"
                                class="px-8 py-3 border-2 border-brand-500 text-brand-500 dark:border-white dark:text-white rounded-lg font-bold hover:bg-brand-500 hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors text-center">
                                Explore Products
                            </a>
                        </div>
                        
                        <div class="flex justify-center md:justify-start mt-8 space-x-2">           
                            <template x-for="(product, index) in products" :key="index">
                            <button class="w-3 h-3 rounded-full"
                                :class="currentIndex === index ? 'bg-brand-500 dark:bg-white' : 'bg-gray-300 dark:bg-gray-600'"
                                @click="currentIndex = index" aria-label="Go to product slide"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Right Image with Scroll Parallax -->
                    <div class="flex items-center justify-center relative overflow-hidden">
                        <div class="relative w-full h-full">
                            <img :src="currentProduct.image" alt="Musical Instruments" class="relative w-full h-auto min-h-full object-contain rounded-2xl shadow-2xl hover:scale-[1.03] transition-transform duration-500 ease-out">
                        </div>
                    </div>
                </div>

                <!-- Dots Navigation -->
                

            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section data-aos="fade-up" class="relative py-12 md:py-16 bg-category-light dark:bg-category-dark overflow-hidden">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-7xl relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Shop by Category
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Find the perfect instrument for your musical journey
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
                @forelse ($categories as $category)
                    <a href="#"
                        class="group card-hover bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                        <div class="relative h-40 bg-gradient-to-br from-brand-400 to-brand-500 overflow-hidden">
                            <div
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 opacity-70 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 13l-7 7-7-7m0 0V6m0 0l7-7m-7 7h12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->products_count }}
                                {{ $category->products_count === 1 ? 'Product' : 'Products' }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-600 dark:text-gray-400">No categories available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section data-aos="fade-up" class="relative py-12 md:py-16 bg-brand-light dark:bg-brand-dark overflow-hidden">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Shop by Brand
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Discover products from trusted and renowned brands
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
                @forelse ($brands as $brand)
                    <a href="#"
                        class="group card-hover bg-gray-50 dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 flex flex-col items-center justify-center text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-brand-400 to-brand-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                                </path>
                            </svg>
                        </div>
                        <h3
                            class="font-bold text-gray-900 dark:text-white text-sm mb-1 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                            {{ $brand->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $brand->products_count }}
                            {{ $brand->products_count === 1 ? 'Product' : 'Products' }}</p>
                    </a>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-600 dark:text-gray-400">No brands available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section data-aos="fade-up" id="products" class="py-12 md:py-16 bg-featured-light dark:bg-featured-dark">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>
        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Featured Products
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                    Handpicked instruments for exceptional quality and value
                </p>
                <a href="{{ route('shop') }}"
                    class="inline-block text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 font-semibold transition-all transform hover:-translate-y-1">
                    View All Products →
                </a>
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        @include('user.components.product-card', [
                            'class' => 'transition-transform hover:scale-105 hover:shadow-xl',
                        ])
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 dark:text-gray-400 text-lg">No products available yet.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section data-aos="fade-up" class="py-12 md:py-16 bg-why-choose-us-light dark:bg-why-choose-us-dark">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span
                class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"> Why Choose KC Musical Store?
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @php
                    $features = [
                        [
                            'color' => 'brand',
                            'title' => 'Quality Guaranteed',
                            'desc' => 'All our instruments are carefully selected and tested for excellence',
                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                        ],
                        [
                            'color' => 'green',
                            'title' => 'Best Prices',
                            'desc' => 'Competitive prices and regular discounts for our valued customers',
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        [
                            'color' => 'purple',
                            'title' => 'Fast Delivery',
                            'desc' => 'Quick and reliable shipping to anywhere in Nigeria',
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        [
                            'color' => 'orange',
                            'title' => 'Expert Support',
                            'desc' => 'Dedicated customer support from music enthusiasts',
                            'icon' =>
                                'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
                        ],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <div class="text-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-{{ $feature['color'] }}-100 dark:bg-{{ $feature['color'] }}-900 rounded-full mb-4 transition-transform transform hover:scale-110">
                            <svg class="w-8 h-8 text-{{ $feature['color'] }}-600 dark:text-{{ $feature['color'] }}-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $feature['icon'] }}"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section data-aos="fade-up" class="py-12 md:py-16 bg-brand-600 dark:bg-brand-900 text-white">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span
                class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-3xl">
            <div class="text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Stay Updated</h2>
                <p class="text-brand-100 text-lg mb-8">Subscribe to our newsletter for exclusive deals and new product
                    launches</p>
                <form class="flex flex-col sm:flex-row gap-3"> @csrf
                    <input type="email" placeholder="Enter your email"
                        class="flex-1 px-6 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-300 font-medium transition-all duration-300 transform focus:-translate-y-1">
                    <button type="submit"
                        class="px-8 py-3 bg-white text-brand-600 rounded-lg font-bold hover:bg-gray-100 transition-all transform hover:-translate-y-1 whitespace-nowrap">
                        Subscribe
                    </button>
                </form>
                <p class="text-sm text-brand-100 mt-4">We respect your privacy. Unsubscribe at any time.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section data-aos="fade-up" class="py-12 md:py-16 bg-testimonials-light dark:bg-testimonials-dark">
        <!-- Decorative stars -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <span
                class="absolute top-12 left-16 w-2 h-2 bg-white rounded-full opacity-30 animate-float-slow animate-twinkle"></span>
            <span
                class="absolute top-40 right-20 w-3 h-3 bg-brand-400 rounded-full opacity-20 animate-float-slower"></span>
            <span
                class="absolute bottom-24 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 animate-float-slow"></span>
            <span
                class="absolute bottom-10 right-1/4 w-2 h-2 bg-brand-300 rounded-full opacity-20 animate-float-slower animate-twinkle"></span>
        </div>

        <div class="px-4 md:px-6 mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"> What Our Customers Say </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach (['Ade Okafor|Guitar Enthusiast|Excellent quality products and fantastic customer service. Would definitely recommend to any musician!', 'Chioma Uche|Music Teacher|Great selection of products and competitive prices. The delivery was fast and the product arrived in perfect condition!', 'Emmanuel Adeleke|Professional Musician|Best place to buy musical instruments online. Professional staff and authentic products. 10/10 experience!'] as $testimonial)
                    @php
                        [$name, $role, $message] = explode('|', $testimonial);
                    @endphp
                    <div
                        class="bg-white dark:bg-gray-700 p-8 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <div class="flex text-brand-500 mb-4 space-x-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">"{{ $message }}"</p>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $role }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
@endsection

@push('styles')
    <style>
        /* Floating animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-float-slow {
            animation: float 8s ease-in-out infinite;
        }

        .animate-float-slower {
            animation: float 12s ease-in-out infinite;
        }

        /* Card hover effect */
        .card-hover {
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function newArrivals() {
            return {
                products: @json($latestProductsData),
                currentIndex: 0,
                get currentProduct() {
                    return this.products[this.currentIndex];
                },
                init() {
                    setInterval(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.products.length;
                    }, 5000); // slide every 5s
                }
            }
        }
    </script>
@endpush
