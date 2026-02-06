<a href="{{ route('product.show', $product->slug) }}" class="block bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group">
    <!-- Product Image -->
    <div class="relative w-full h-64 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        <img src="{{ $product->image ? asset('uploads/products/' . $product->image) : asset('images/placeholder.jpg') }}"
            alt="{{ $product->name }}"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

        @if ($product->sale_price && $product->sale_price < $product->regular_price)
            <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                -{{ round((1 - $product->sale_price / $product->regular_price) * 100) }}%
            </div>
        @endif

        @if ($product->featured)
            <div class="absolute top-3 left-3 bg-brand-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Featured
            </div>
        @endif

        <!-- Quick View Overlay -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
            <button class="bg-white text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Quick View
            </button>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-4">
        <!-- Brand -->
        @if ($product->brand)
            <p class="text-xs font-semibold text-brand-600 dark:text-brand-400 uppercase tracking-wide mb-2">
                {{ $product->brand->name }}
            </p>
        @endif

        <!-- Name -->
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
            {{ $product->name }}
        </h3>

        <!-- Rating -->
        <div class="flex items-center gap-1 mb-3">
            <div class="flex text-yellow-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(48)</span>
        </div>

        <!-- Price -->
        <div class="flex items-center gap-2 mb-4">
            @if ($product->sale_price)
                <span class="text-lg font-bold text-gray-900 dark:text-white">
                    ₦{{ number_format($product->sale_price, 2) }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400 line-through">
                    ₦{{ number_format($product->regular_price, 2) }}
                </span>
            @else
                <span class="text-lg font-bold text-gray-900 dark:text-white">
                    ₦{{ number_format($product->regular_price, 2) }}
                </span>
            @endif
        </div>

        <!-- Stock Status -->
        @if ($product->stock_status === 'instock')
            <span class="inline-block text-xs font-semibold text-green-600 dark:text-green-400 mb-4">
                ✓ In Stock 
            </span>
        @elseif ($product->quantity === 0)
            <span class="inline-block text-xs font-semibold text-red-600 dark:text-red-400 mb-4">
                ✕ Out of Stock
            </span>
        @elseif ($product->isPreorder)
            <span class="inline-block text-xs font-semibold text-yellow-600 dark:text-yellow-400 mb-4">
                ✓ Pre-order
            </span>
        @else
            <span class="inline-block text-xs font-semibold text-red-600 dark:text-red-400 mb-4">
                ✕ Out of Stock
            </span>
        @endif

        <!-- Add to Cart Button -->
        <button @click.prevent="{{ $product->getAvailableQuantityAttribute() <= 0 ? 'alert(\'Sorry, this product is out of stock or reserved by other customers.\')' : '
            (() => {
                const added = $store.cart.addItem({
                    id: '.$product->id.',
                    name: \''.$product->name.'\',
                    price: '.($product->sale_price ?? $product->regular_price).',
                    image: \''.$product->image.'\',
                    quantity: 1,
                    quantity_limit: '.$product->getAvailableQuantityAttribute().'
                });
                if(added) {
                    alert("Product added to cart successfully!");
                }
            })()
        '}}" 
            class="w-full py-2 px-4 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-semibold transition-colors duration-300"
            >
            Add to Cart
        </button>
    </div>
</a>
