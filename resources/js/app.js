import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import '../css/app.css';


// flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
// FullCalendar
import { Calendar } from '@fullcalendar/core';
// nigeria states and LGAs
import { NigeriaStatesAndLGAs } from './nigeria-states-lgas';


window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;
window.FullCalendar = Calendar;
// store/cart.js or app.js
document.addEventListener('alpine:init', () => {

    // 🛒 Cart store
    Alpine.store('cart', {
        // Get user ID from meta tag or default to 'guest'
        userId: document.querySelector('meta[name="user-id"]')?.content || 'guest',
        
        // Add mobile menu state
        mobileMenuOpen: false,
        
        items: JSON.parse(localStorage.getItem('cart_items') || '[]'),

        // Initialize cart with user ID
        init() {
            // Check if cart exists for this user
            const cartData = JSON.parse(localStorage.getItem('cart_data') || '{}');
            
            // If cart exists for this user, use it, otherwise start fresh
            if (cartData.userId === this.userId) {
                this.items = cartData.items || [];
            } else {
                this.items = [];
            }
        },

        // Add new item or increase quantity
        addItem(product) {
            const existing = this.items.find(item => item.id === product.id);
            const maxQuantity = product.quantity_limit ?? 1000;

            if (existing) {
                const newQuantity = existing.quantity + (product.quantity ?? 1);

                if (newQuantity > maxQuantity) {
                    alert(`You cannot add more than ${maxQuantity} of this product.`);
                    return false;
                }

                existing.quantity = newQuantity;
            } else {
                const qty = product.quantity ?? 1;
                if (qty > maxQuantity) {
                    alert(`You cannot add more than ${maxQuantity} of this product.`);
                    return false;
                }
                this.items.push({ ...product, quantity: qty });
            }

            this.save();
            return true;
        },

        // Save and force reactivity
        save() {
            // Save user ID with cart data
            const cartData = {
                userId: this.userId,
                items: this.items
            };
            
            localStorage.setItem('cart_data', JSON.stringify(cartData));
            localStorage.setItem('cart_items', JSON.stringify(this.items));
            this.items = [...this.items]; // important for Alpine reactivity
        },

        // Load from localStorage
        loadCart() {
            const cartData = JSON.parse(localStorage.getItem('cart_data') || '{}');
            
            // If cart exists for this user, use it, otherwise start fresh
            if (cartData.userId === this.userId) {
                this.items = cartData.items || [];
            } else {
                this.items = [];
            }
            
            // Also save the current state to ensure it's updated
            this.save();
        },

        // Get total count
        get count() {
            return this.items.reduce((sum, i) => sum + i.quantity, 0);
        },

        // Get subtotal
        get subtotal() {
            return this.items.reduce((sum, i) => sum + i.price * i.quantity, 0);
        },

        // Update cart item quantity
        updateCart(id, quantity) {
            const item = this.items.find(i => i.id === id);
            if (item) {
                if (quantity <= 0) {
                    this.removeItem(id);
                } else {
                    // Check if quantity is within available limit
                    if (item.quantity_limit && quantity > item.quantity_limit) {
                        alert(`Cannot increase quantity. Only ${item.quantity_limit} ${item.quantity_limit === 1 ? 'unit' : 'units'} available for ${item.name}.`);
                        return false;
                    }
                    item.quantity = quantity;
                    this.save();
                }
            }
        },

        // Check available quantity for a specific item
        async checkAvailableQuantity(productId) {
            try {
                const response = await fetch(`/api/product/${productId}`);
                const product = await response.json();
                
                if (!response.ok) {
                    throw new Error(`Failed to fetch product: ${response.status}`);
                }
                
                return product.available_quantity || 0;
            } catch (error) {
                console.error('Error fetching product availability:', error);
                return 0; // Default to 0 if there's an error
            }
        },

        // Update quantity with availability check
        async updateQuantityWithCheck(id, newQuantity) {
            const item = this.items.find(i => i.id === id);
            if (!item) return false;
            
            // Get current available quantity from backend
            const availableQuantity = await this.checkAvailableQuantity(item.id);
            
            // Calculate how many items are already in the cart
            const alreadyInCart = item.quantity;
            
            // Calculate additional items requested
            const additionalRequested = newQuantity - alreadyInCart;
            
            if (additionalRequested > availableQuantity) {
                const msg = `Only ${availableQuantity} ${availableQuantity === 1 ? 'unit' : 'units'} available for ${item.name}. You already have ${alreadyInCart} in your cart.`;
                alert(msg);
                return false;
            }
            
            // Update the quantity limit based on new availability
            item.quantity_limit = availableQuantity + alreadyInCart;
            
            if (newQuantity <= 0) {
                this.removeItem(id);
                return true;
            }
            
            if (newQuantity > item.quantity_limit) {
                alert(`Cannot increase quantity. Only ${item.quantity_limit} ${item.quantity_limit === 1 ? 'unit' : 'units'} available for ${item.name}.`);
                return false;
            }
            
            item.quantity = newQuantity;
            this.save();
            return true;
        },

        // Clear the cart for the current user
        clearCart() {
            // Clear localStorage
            localStorage.removeItem('cart_data');
            localStorage.removeItem('cart_items');
            
            // Clear the cart items
            this.items = [];
            this.save();
            
            // Update cart badge count if exists
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = '0';
            }
        },

        // Remove a specific item from the cart
        removeItem(id) {
            this.items = this.items.filter(item => item.id !== id);
            this.save();
        }
    });

    // Load cart immediately on init
    Alpine.store('cart').loadCart();
    
    // Add event listener for pagehide to ensure cart is saved
    window.addEventListener('pagehide', () => {
        Alpine.store('cart').save();
    });
    
    // Make sure updateCart is available in the cart store
    Alpine.store('cart').updateCart = function(id, quantity) {
        const item = this.items.find(i => i.id === id);
        if (item) {
            if (quantity <= 0) {
                this.removeItem(id);
            } else {
                item.quantity = quantity;
                this.save();
            }
        }
    };
});

Alpine.data('cartItemControls', () => ({
    // your cart items
    items: Alpine.store('cart').items,

    // increment item quantity
    async increaseQuantity(item) {
        const store = Alpine.store('cart');
        const newQty = item.quantity + 1;

        // Check availability before increasing quantity
        const availableQuantity = await store.checkAvailableQuantity(item.id);
        const alreadyInCart = item.quantity;
        
        if (newQty > (availableQuantity + alreadyInCart)) {
            alert(`Only ${availableQuantity} ${availableQuantity === 1 ? 'unit' : 'units'} available for ${item.name}. You already have ${alreadyInCart} in your cart.`);
            return;
        }

        store.updateCart(item.id, newQty);
    },

    // decrement item quantity
    decreaseQuantity(item) {
        if (item.quantity <= 1) {
            if (confirm(`Are you sure you want to remove "${item.name}" from your cart?`)) {
                Alpine.store('cart').removeItem(item.id);
            }
            return;
        }

        const store = Alpine.store('cart');
        store.updateCart(item.id, item.quantity - 1);
    },  

    removeItem(item) {
        Alpine.store('cart').removeItem(item.id);
    }
}));


//window.NigeriaStatesAndLGAs = NigeriaStatesAndLGAs;
Alpine.data('checkoutForm', () => ({
    // CUSTOMER INFO
    full_name: '',
    phone: '',
    street: '',
    landmark: '',
    description: '',
    additional_info: '',

    // LOCATION
    selectedState: '',
    selectedLga: '',

    // PAYMENT
    paymentMethod: '', // 'cod' or 'paystack'

    // SHIPPING
    shippingPrice: 0,

    // TRACK SAVED ADDRESS
    savedAddress: null,
    addressId: null, // will store the ID returned from saveAddress()

    // STATES & LGAs
    states: Object.keys(NigeriaStatesAndLGAs),
    lgas: [],

    // Initialize with last saved address if it exists
    async init() {
        try {
            // Fetch saved address
            const res = await fetch('/checkout/saved-address');
            if (res.ok) {
                const data = await res.json();
                if (data.address) {
                    this.savedAddress = data.address;
                    this.addressId = data.address.id;
                }
            }

            // Populate state options
            await this.updateStates();

            // Wait for next DOM update
            await this.$nextTick();

            // Set selected state
            this.selectedState = this.savedAddress?.state || '';

            // Update LGAs
            await this.updateLgas();

            // Wait for next DOM update
            await this.$nextTick();

            // Now populate all form fields
            if (this.savedAddress) {
                this.full_name = this.savedAddress.full_name || '';
                this.phone = this.savedAddress.phone || '';
                this.selectedLga = this.savedAddress.lga || '';
                this.street = this.savedAddress.street || '';
                this.landmark = this.savedAddress.landmark || '';
                this.description = this.savedAddress.description || '';
                this.additional_info = this.savedAddress.additional_info || '';
            }
        } catch (err) {
            console.error('Failed to initialize form:', err);
        }
    },

    // -----------------------------
    // LGA HANDLING
    // -----------------------------
    async updateLgas() {
        this.selectedLga = '';
        this.shippingPrice = 0;

        if (!this.selectedState) return;

        if (this.selectedState === 'Lagos') {
            try {
                const res = await fetch('/api/lagos-locations');
                this.lgas = (await res.json()).map((lga, index) => ({
                    id: index,
                    name: lga.name,
                    shipping_price: lga.shipping_price
                }));
            } catch (e) {
                console.error(e);
            }
            return;
        }

        this.lgas = (NigeriaStatesAndLGAs[this.selectedState] || []).map((name, index) => ({
            id: index,
            name
        }));

        this.fetchShippingPrice();
    },

    // Update STATES HANDLING to fetch states from API
    async updateStates() {
        try {
            const res = await fetch('/checkout/states');
            if (!res.ok) throw new Error('Failed to fetch states');
            const data = await res.json();
            
            // Assuming data is an array of state names
            this.states = data;
        } catch (e) {
            console.error('Failed to fetch states:', e);
            // Fallback to local NigeriaStatesAndLGAs
            this.states = Object.keys(NigeriaStatesAndLGAs);
        }
    },

    // -----------------------------
    // FETCH SHIPPING PRICE
    // -----------------------------
    fetchShippingPrice() {
        if (!this.selectedState) return;

        fetch('/checkout/shipping-price', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                state: this.selectedState,
                lga: this.selectedState === 'Lagos' ? this.selectedLga : null,
                items: Alpine.store('cart').items.map(i => ({
                    product_id: i.id,
                    quantity: i.quantity
                }))
            })
        })
        .then(res => res.json())
        .then(data => {
            this.shippingPrice = Number(data.shipping_price) || 0;
        })
        .catch(err => {
            console.error(err);
            this.shippingPrice = 0;
        });
    },

    // -----------------------------
    // SAVE ADDRESS FIRST
    // -----------------------------
    
    async saveAddress() {
        const res = await fetch('/checkout/address', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                customer: {
                    full_name: this.full_name,
                    phone: this.phone
                },
                address: {
                    state: this.selectedState,
                    lga: this.selectedLga,
                    street: this.street,
                    landmark: this.landmark,        // Adding landmark field
                    description: this.description,
                    additional_info: this.additional_info   // Adding additional_info field
                }
            })
        });

        const data = await res.json();

        if (!res.ok) {
            console.error(data);
            alert(data.message);
            return;
        }
          this.savedAddress = data.address;
        this.addressId = data.address.id;
    },

        editAddress() {
        this.savedAddress = null;
        this.addressId = null;
    },
    // -----------------------------
    // PLACE ORDER
    // -----------------------------
 async submitOrder() {
    // Check if any cart items are preorder and payment method is COD
    const cartItems = Alpine.store('cart').items;
    let hasPreorderItems = false;
    
    // Check if any item is preorder
    for (const item of cartItems) {
        const response = await fetch(`/api/product/${item.id}`);
        const product = await response.json();
        
        if (product.stock_status === 'preorder') {
            hasPreorderItems = true;
            break;
        }
    }
    
    // If preorder items exist and payment method is COD, show alert
    if (hasPreorderItems && this.paymentMethod === 'cod') {
        alert('Cash on Delivery (COD) is not available for preorder items. Please select Paystack as your payment method.');
        return;
    }

    if (!this.addressId) {
        alert('Please save your address first.');
        return;
    }
    

    try {
        console.log("Placing order...");

        const res = await fetch('/checkout/order', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                address_id: this.addressId,
                items: this.cartItems.map(i => ({
                    product_id: i.id,
                    quantity: i.quantity,
                    price: i.price
                })),
                shipping_price: this.shippingPrice,
                payment_method: this.paymentMethod
            })
        });

        let data;

        try {
            // Attempt to parse JSON
            data = await res.json();
        } catch (jsonError) {
            // If response is not valid JSON, log full text for debugging
            const text = await res.text();
            console.error("Server returned non-JSON response:", text);
            alert('Server returned invalid JSON. Check console for full response.');
            return;
        }

        console.log("Full response from backend:", data); // ✅ Full backend output

        if (!res.ok) {
            console.error("Order placement error:", data);
            alert(data.message || 'Failed to place order. Check console for details.');
            return;
        }

        // Success handling
        if (data.success) {
            if (data.payment_url) {
                console.log("Redirecting to payment:", data.payment_url);
                window.clearCart();
                window.location.href = data.payment_url;
            } else if (data.redirect_url) {
                console.log("Redirecting to confirmation:", data.redirect_url);
                window.clearCart();
                window.location.href = data.redirect_url;
            } else {
                console.warn("No redirect URL provided in response");
            }
        } else {
            console.error("Order failed:", data);
            alert(data.message || 'Order failed. Check console for details.');
        }
    } catch (e) {
        console.error("Unexpected error in submitOrder:", e);
        alert('Unexpected error occurred while placing order. Check console for details.');
    }
},

    // -----------------------------
    // CART COMPUTED
    // -----------------------------
    get cartItems() {
        return Alpine.store('cart').items;
    },

    get cartSubtotal() {
        return this.cartItems.reduce((sum, i) => sum + i.price * i.quantity, 0);
    },

    get grandTotal() {
        return this.cartSubtotal + this.shippingPrice;
    }
}));





Alpine.start();

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Map imports
    // if (document.querySelector('#mapOne')) {
    //     import('./components/map').then(module => module.initMap());
    // }

    // Chart imports
    // if (document.querySelector('#chartOne')) {
    //     import('./components/chart/chart-1').then(module => module.initChartOne());
    // }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
});

// Global function to clear the cart
window.clearCart = async function() {
    try {
        // Call the backend to clear session cart
        
   
                // Clear the localStorage as well
                localStorage.removeItem('cart_items');
                
                // Clear the Alpine store
                Alpine.store('cart').items = [];
                
                // Also call the save method to persist the empty cart
                if (typeof Alpine.store('cart').save === 'function') {
                    Alpine.store('cart').save();
                }
            
            
            // Update cart badge count if exists
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = '0';
            }
            
            console.log('Cart cleared successfully');
            return true;
        }
     catch (error) {
        console.error('Error clearing cart:', error);
        return false;
    }
};
