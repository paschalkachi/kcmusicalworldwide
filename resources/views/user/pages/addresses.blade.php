@extends('user.layouts.app', ['title' => $title])

@section('content')
<!-- Page Header -->
<section class="text-black dark:text-white py-12 md:py-16">
    <div class="px-4 md:px-6 mx-auto max-w-7xl">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $title }}</h1>
        <p class="text-light-black text-lg">Manage your shipping addresses</p>
    </div>
</section>

<!-- Addresses Content -->
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
                <a href="{{ route('user.addresses') }}" class="block px-4 py-3 rounded-lg bg-brand-600 text-white font-medium transition-colors">
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
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Your Addresses</h2>
                    <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                        Add New Address
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($defaultAddress)
                        <div class="border-2 border-blue-500 dark:border-blue-400 rounded-lg p-6 bg-blue-50 dark:bg-blue-900/20" id="address-{{ $defaultAddress->id }}">
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
                            <div class="flex gap-4">
                                <button class="set-default-btn text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium" data-id="{{ $defaultAddress->id }}" disabled title="This is already the default">
                                    Default
                                </button>
                                <button class="edit-btn text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-sm font-medium" data-id="{{ $defaultAddress->id }}">
                                    Edit
                                </button>
                                <button class="delete-btn text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium" data-id="{{ $defaultAddress->id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endif

                    @forelse($otherAddresses as $address)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6" id="address-{{ $address->id }}">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Address #{{ $address->id }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                {{ $address->full_name }}<br>
                                {{ $address->street ? $address->street.',' : '' }}
                                {{ $address->lga ? $address->lga.',' : '' }}
                                {{ $address->state ? $address->state->name.',' : '' }}
                                Nigeria
                            </p>
                            <div class="flex gap-4">
                                <button class="set-default-btn text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium" data-id="{{ $address->id }}">
                                    Make Default
                                </button>
                                <button class="edit-btn text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 text-sm font-medium" data-id="{{ $address->id }}">
                                    Edit
                                </button>
                                <button class="delete-btn text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium" data-id="{{ $address->id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @empty
                        @if(!$defaultAddress)
                            <div class="md:col-span-2 text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No addresses</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    You haven't added any shipping addresses yet.
                                </p>
                                <div class="mt-6">
                                    <button class="px-4 py-2 bg-brand-600 hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-800 text-white rounded-lg font-medium transition-colors text-sm">
                                        Add Your First Address
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="addressEditModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Edit Address
                        </h3>
                        <div class="mt-4">
                            <form id="addressEditForm">
                                @csrf
                                <input type="hidden" id="address_edit_address_id">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                    <input type="text" id="address_edit_full_name" name="full_name" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                    <input type="text" id="address_edit_phone" name="phone" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Street</label>
                                    <input type="text" id="address_edit_street" name="street" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LGA</label>
                                        <input type="text" id="address_edit_lga" name="lga" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                                        <select id="address_edit_state_id" name="state_id" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">Select State</option>
                                            @foreach(\App\Models\State::all() as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Landmark (Optional)</label>
                                    <input type="text" id="address_edit_landmark" name="landmark"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <textarea id="address_edit_description" name="description" required rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white"></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Info (Optional)</label>
                                    <textarea id="address_edit_additional_info" name="additional_info" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveAddressEditBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-600 text-base font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Changes
                </button>
                <button type="button" id="cancelAddressEditBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
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
    
    // Handle "Make Default" button clicks
    document.querySelectorAll('.set-default-btn:not([disabled])').forEach(button => {
        button.addEventListener('click', function() {
            const addressId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if(confirm('Are you sure you want to make this address your default?')) {
                fetch(`/addresses/${addressId}/set-default`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Show success message
                        alert(data.message);
                        
                        // Reload the page to reflect changes
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Could not update default address'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the default address.');
                });
            }
        });
    });
    
    // Handle "Edit" button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const addressId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Fetch address data
            fetch(`/addresses/${addressId}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Populate form with address data
                    document.getElementById('edit_address_id').value = data.data.id;
                    document.getElementById('edit_full_name').value = data.data.full_name;
                    document.getElementById('edit_phone').value = data.data.phone;
                    document.getElementById('edit_street').value = data.data.street;
                    document.getElementById('edit_lga').value = data.data.lga;
                    document.getElementById('edit_state_id').value = data.data.state_id;
                    document.getElementById('edit_landmark').value = data.data.landmark || '';
                    document.getElementById('edit_description').value = data.data.description;
                    document.getElementById('edit_additional_info').value = data.data.additional_info || '';
                    
                    // Show modal
                    document.getElementById('addressEditModal').classList.remove('hidden');
                } else {
                    alert('Error: ' + (data.message || 'Could not load address data'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading the address data.');
            });
        });
    });
    
    // Handle "Cancel" button click for edit modal
    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        document.getElementById('addressEditModal').classList.add('hidden');
    });
    
    // Handle "Save" button click for edit form
    document.getElementById('saveAddressEditBtn').addEventListener('click', function() {
        const addressId = document.getElementById('address_edit_address_id').value;
        const formData = new FormData(document.getElementById('addressEditForm'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/addresses/${addressId}`, {
            method: 'PUT',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Show success message
                alert(data.message);
                
                // Hide modal
                document.getElementById('editModal').classList.add('hidden');
                
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Could not update address'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the address.');
        });
    });
    
    // Handle "Delete" button clicks
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const addressId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if(confirm('Are you sure you want to delete this address?')) {
                fetch(`/addresses/${addressId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Show success message
                        alert(data.message);
                        
                        // Remove the address from the DOM
                        const addressElement = document.getElementById(`address-${addressId}`);
                        if(addressElement) {
                            addressElement.remove();
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Could not delete address'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the address.');
                });
            }
        });
    });
});
</script>

@endsection