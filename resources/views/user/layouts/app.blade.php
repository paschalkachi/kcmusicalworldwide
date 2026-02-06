<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'KC Musical Store' }} | KCMUSICAL WORLDWIDE </title>

    <!-- Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

{{-- AOS Library --}}
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Alpine Zoom helper -->
<style>
    .zoom-img {
        transition: transform 0.3s ease;
        cursor: zoom-in;
    }
    .zoom-container:hover .zoom-img {
        transform: scale(1.8);
        cursor: zoom-out;
    }
</style>

<style>
@keyframes floatSlow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes floatSlower {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-25px); }
}

@keyframes twinkle {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.6; }
}

.animate-float-slow {
    animation: floatSlow 6s ease-in-out infinite;
}

.animate-float-slower {
    animation: floatSlower 9s ease-in-out infinite;
}

.animate-twinkle {
    animation: twinkle 3s ease-in-out infinite;
}
</style>


   <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-fZ_zkYKj.css') }}">
   <link rel="stylesheet" href="{{ asset('build/assets/app-CksuuEqD.css') }}">
    <script type="module" src="{{ asset('build/assets/app-FJjOInpK.js') }}"></script>
    <script type="module" src="{{ asset('build/assets/chart-2-DQXQOavV.js') }}"></script>

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-950');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-950');
                    }
                }
            });

            // Alpine.store('cart', {
            //     items: JSON.parse(localStorage.getItem('cart') || '[]'),
            //     mobileMenuOpen: false,
            //     addItem(product) {
            //         const existingItem = this.items.find(item => item.id === product.id);
            //         if (existingItem) {
            //             existingItem.quantity += 1;
            //         } else {
            //             this.items.push({ ...product, quantity: 1 });
            //         }
            //         this.saveCart();
            //     },
            //     removeItem(productId) {
            //         this.items = this.items.filter(item => item.id !== productId);
            //         this.saveCart();
            //     },
            //     saveCart() {
            //         localStorage.setItem('cart', JSON.stringify(this.items));
            //     },
            //     get count() {
            //         return this.items.reduce((sum, item) => sum + item.quantity, 0);
            //     }
            //  });
        // }
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-950');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-950');
            }
        })();
    </script>
</head>

<body class="bg-white dark:bg-gray-950"
    x-data="{ 'loaded': true}"
    x-init="">

    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        @include('user.layouts.header')

        <!-- Navigation -->
        {{-- @include('user.layouts.navbar') --}}

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

      
    </div>

    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="//unpkg.com/alpinejs" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- AOS Library --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'slide',
            once: true,
        });
    </script>
</body>
@include('user.layouts.footer')

@stack('scripts')

</html>
