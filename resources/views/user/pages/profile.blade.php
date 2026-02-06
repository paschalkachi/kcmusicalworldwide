@extends('user.layouts.app', ['title' => 'My Profile'])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">My Profile</h1>
        <p class="text-light-black text-lg">Manage your account and view your orders</p>
    </div>
</section>

<!-- Profile Content -->
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
        <div id="sidebar-nav" class="lg:col-span-1 hidden lg:block absolute lg:relative z-10 w-64 lg:w-auto bg-white dark:bg-category-dark lg:bg-transparent p-6 rounded-xl shadow-md lg:shadow-none mt-2 lg:mt-0 ml-4 lg:ml-0">
            <div class="space-y-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                <a href="{{ route('profile') }}" class="block px-4 py-3 rounded-lg bg-brand-600 text-white font-medium transition-colors">
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
        <div class="lg:col-span-3 space-y-8">
            <!-- Profile Image Section -->
            <div class="bg-category-light dark:bg-category-dark rounded-xl shadow-md p-6 md:p-8">
                <div class="flex items-center">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full bg-gray-200 border-2 border-dashed flex items-center justify-center overflow-hidden">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-500 text-3xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 bg-brand-600 rounded-full p-1 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="bg-category-light dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Account Information</h2>
                    <button id="toggleEditBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                        Edit Profile
                    </button>
                </div>

                <form id="updateProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" 
                                   value="{{ explode(' ', $user->name)[0] ?? '' }}"
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white disabled:opacity-70">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" 
                                   value="{{ implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '' }}"
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white disabled:opacity-70">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" 
                                   value="{{ $user->email }}" 
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white disabled:opacity-70">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ $user->phone ?? '' }}"
                                   placeholder="+234..." 
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 disabled:opacity-70">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Profile Image</label>
                            <input type="file" id="profile_image" name="profile_image" 
                                   accept="image/*"
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 disabled:opacity-70">
                        </div>
                    </div>

                    <div id="updateButtons" class="hidden flex gap-4">
                        <button type="submit" class="px-8 py-3 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-bold transition-colors">
                            Update Profile
                        </button>
                        <button type="button" id="cancelEditBtn" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white rounded-lg font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order History -->
            <div class="bg-category-light dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Recent Orders</h2>

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
                                        No orders yet. <a href="{{ route('shop') }}" class="text-brand-600 dark:text-brand-400 hover:underline">Start shopping</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Addresses -->
            <div class="bg-category-light dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Default Shipping Address</h2>
                    <a href="{{ route('user.addresses') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                        Manage Addresses
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($defaultAddress)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 md:col-span-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Default Address</h3>
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold">Default</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                {{ $defaultAddress->full_name }}<br>
                                {{ $defaultAddress->street ? $defaultAddress->street.',' : '' }}
                                {{ $defaultAddress->lga ? $defaultAddress->lga.',' : '' }}
                                {{ $defaultAddress->state ? $defaultAddress->state->name.',' : '' }}
                                Nigeria
                            </p>
                        </div>
                    @else
                        <div class="md:col-span-2 text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">No default address set.</p>
                            <a href="{{ route('user.addresses') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                                Add Your First Address
                            </a>
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

document.getElementById('toggleEditBtn').addEventListener('click', function() {
    document.querySelectorAll('#updateProfileForm input').forEach(input => {
        input.disabled = false;
    });
    document.getElementById('updateButtons').classList.remove('hidden');
    document.getElementById('toggleEditBtn').classList.add('hidden');
});

document.getElementById('cancelEditBtn').addEventListener('click', function() {
    document.querySelectorAll('#updateProfileForm input').forEach(input => {
        input.disabled = true;
    });
    document.getElementById('updateButtons').classList.add('hidden');
    document.getElementById('toggleEditBtn').classList.remove('hidden');
});

</script>

    <!-- Scripts -->
    <script>
        // Define the global variables
        window.APP = {
            name: 'KCMUSIC STORE',
            base_url: '{{ url('/') }}',
            asset_url: '{{ asset('/') }}',
            csrf_token: '{{ csrf_token() }}',  // Fixed: Added parentheses to call the function
        };
    </script>
    
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
        
        // Profile image upload functionality would go here if needed
    });
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('updateProfileForm');
        const toggleEditBtn = document.getElementById('toggleEditBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const updateButtons = document.getElementById('updateButtons');
        const formInputs = form.querySelectorAll('input');

        // Toggle edit mode
        function toggleEdit() {
            const isDisabled = formInputs[0].disabled;
            
            formInputs.forEach(input => {
                input.disabled = !isDisabled;
                if(isDisabled) {
                    input.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'disabled:opacity-70');
                    input.classList.add('bg-white', 'dark:bg-gray-700');
                } else {
                    input.classList.add('bg-gray-50', 'dark:bg-gray-700', 'disabled:opacity-70');
                    input.classList.remove('bg-white', 'dark:bg-gray-700');
                }
            });

            if(isDisabled) {
                updateButtons.classList.remove('hidden');
                toggleEditBtn.innerHTML = 'Cancel';
                toggleEditBtn.onclick = function() {
                    // Reset form to original values when canceling
                    form.reset();
                    toggleEdit();
                };
            } else {
                updateButtons.classList.add('hidden');
                toggleEditBtn.innerHTML = 'Edit Profile';
                toggleEditBtn.onclick = function() {
                    toggleEdit();
                };
            }
        }

        // Event listeners
        toggleEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleEdit();
        });

        cancelEditBtn.addEventListener('click', function() {
            // Reset form to original values
            form.reset();
            toggleEdit();
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route('profile.update') }}', {
                method: 'PATCH',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success message
                    alert(data.message);
                    
                    // Update display name in case it changed
                    const firstName = document.getElementById('first_name').value;
                    const lastName = document.getElementById('last_name').value;
                    const fullName = firstName + (lastName ? ' ' + lastName : '');
                    
                    // Update the display name at the top if it exists
                    const displayName = document.querySelector('h2.text-2xl.font-bold.text-gray-900.dark\\:text-white');
                    if(displayName) {
                        displayName.textContent = fullName;
                    }
                    
                    // Update email if it changed
                    const emailDisplay = document.querySelector('.text-gray-600.dark\\:text-gray-400');
                    if(emailDisplay) {
                        emailDisplay.textContent = document.getElementById('email').value;
                    }
                    
                    // Disable inputs again
                    formInputs.forEach(input => {
                        input.disabled = true;
                        input.classList.remove('bg-white', 'dark:bg-gray-700');
                        input.classList.add('bg-gray-50', 'dark:bg-gray-700', 'disabled:opacity-70');
                    });
                    
                    // Hide update/cancel buttons
                    updateButtons.classList.add('hidden');
                    
                    // Change button text back to "Edit Profile"
                    toggleEditBtn.innerHTML = 'Edit Profile';
                    toggleEditBtn.onclick = function() {
                        toggleEdit();
                    };
                } else {
                    alert('Error: ' + (data.message || 'Could not update profile'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the profile.');
            });
        });
    });
    </script>
@endsection