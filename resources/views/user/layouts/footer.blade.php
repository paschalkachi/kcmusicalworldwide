<footer class="text-gray-300 bg-footer-light dark:bg-footer-dark" :style="{ 'color': '#d1d5db' }">
    <!-- Main Footer Content -->
    <div class="px-4 md:px-6 py-12 md:py-16 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 md:gap-12">
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <a href="/" class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors">                    
                    <img class="dark:block w-40 h-auto" src="/images/logo/logo-dark.png" alt="Logo" />
                </a>
                <p class="text-sm leading-relaxed mb-6 mt-5">
                    Your one-stop shop for premium musical instruments and accessories. Quality products at competitive prices.
                </p>
                <div class="flex gap-4">
                   <a
                        href="{{ $adminUser?->social_links['facebook'] ?? 'https://www.facebook.com/Kcmusicalworldwide' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors"
>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
                    </a>

                    <a
                        href="{{ $adminUser?->social_links['tiktok'] ?? '#' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors"
>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 002.856-3.51 10 10 0 01-2.856.97 4.96 4.96 0 002.165-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path></svg>
                    </a>
                    
                    <a
                        href="{{ $adminUser?->social_links['whatsapp'] ?? 'https://wa.me/2348068273684' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors"
>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Shop Links -->
            <div>
                <h3 class="text-white font-semibold mb-4">Shop</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('shop') }}" class="hover:text-white transition-colors">All Products</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">New Arrivals</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Best Sellers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">On Sale</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Deals</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-white font-semibold mb-4">Categories</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Guitars</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Keyboards</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Drums</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Strings & Picks</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Accessories</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h3 class="text-white font-semibold mb-4">Customer Service</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Warranty</a></li>
                </ul>
            </div>

            <!-- Contact & Newsletter -->
            <div>
                <h3 class="text-white font-semibold mb-4">Contact</h3>
                <ul class="space-y-3 text-sm mb-6">
                    <li>
                        <a href="mailto:{{ \App\Models\User::whereHas('roles', function($query) { $query->where('roles.id', 1); })->first()?->email ?: 'info@kcmusical.com' }}" class="hover:text-white transition-colors">{{ \App\Models\User::whereHas('roles', function($query) { $query->where('roles.id', 1); })->first()?->email ?: 'info@kcmusical.com' }}</a>
                    </li>
                    <li>
                        <a href="tel:+{{ preg_replace('/[^0-9]/', '', \App\Models\User::whereHas('roles', function($query) { $query->where('roles.id', 1); })->first()?->phone ?: '234123456789') }}" class="hover:text:white transition-colors">{{ \App\Models\User::whereHas('roles', function($query) { $query->where('roles.id', 1); })->first()?->phone ?: '+234 (123) 456-789' }}</a>
                    </li>
                    <li class="text-gray-400">
                        @php
                            $address = \App\Models\User::whereHas('roles', function($query) { $query->where('roles.id', 1); })->first()?->address;
                            $formattedAddress = '123 Music Street<br>Lagos, Nigeria'; // Default fallback
                            if ($address) {
                                if (is_array($address)) {
                                    // If it's already decoded as an array
                                    $formattedAddress = implode(', ', array_filter([
                                        $address['business_address'] ?? null,
                                        $address['city'] ?? null,
                                        $address['state'] ?? null,
                                        $address['country'] ?? null
                                    ]));
                                } else {
                                    // If it's still a JSON string, decode it
                                    $decodedAddress = json_decode($address, true);
                                    if ($decodedAddress) {
                                        $formattedAddress = implode(', ', array_filter([
                                            $decodedAddress['business_address'] ?? null,
                                            $decodedAddress['city'] ?? null,
                                            $decodedAddress['state'] ?? null,
                                            $decodedAddress['country'] ?? null
                                        ]));
                                    }
                                }
                            }
                        @endphp
                        {!! $formattedAddress !!}
                    </li>
                </ul>
                <h4 class="text-white font-semibold mb-3">Newsletter</h4>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Your email"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg font-medium transition-colors text-sm">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 my-8 md:my-12"></div>

        <!-- Bottom Footer -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-400">
            <p>&copy; <span id="year"></span> KCMusicalWorldwide. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Cookie Settings</a>
            </div>
            
        </div>

     
        <span class="flex justify-center mt-4">
          <p class="text-sm">
            Built by 
            <a href="https://github.com/paschalkachi" target="_blank" class="hover:text-brand-500">
              Paschal Kachi @Prexivel Technologies
            </a> 
          </p>
        </span>        

    </div>
</footer>

@push('scripts')
    <script>
        // Set current year in footer
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>    
@endpush
