<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LagosShippingLocationsController;  // Using the correct controller
use App\Http\Controllers\Admin\SKUController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\AdminOrdersController;
use App\Http\Controllers\Admin\AdminUserController; // Adding the new controller
use App\Http\Controllers\Admin\ShippingClassController; // Adding the new shipping class controller
use App\Http\Controllers\Admin\AdminProfileController; // Adding the admin profile controller
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\LagosLocationController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutShippingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Intervention\Image\Image as InterventionImage;

/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| Guest Routes (Signup / Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])
        ->name('signup');

    Route::post('/signup', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});
 

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/admin'); // redirect after verification
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    //Route::post('/checkout/shipping-info', [CheckoutController::class, 'shippingInfo']);

    //Route::get('/checkout/lagos-lgas', [CheckoutController::class, 'lagosLocations']);
    Route::post('/checkout/shipping-price', [CheckoutController::class, 'shippingInfo'])->name('checkout.shipping-price');

    Route::post('/checkout/address', [CheckoutController::class, 'saveAddress']) ->name('checkout.save-address');
    Route::get('/checkout/saved-address', [CheckoutController::class, 'getSavedAddress'])->name('checkout.savedAddress');

    //Route::get('/checkout/address/last', [CheckoutController::class, 'getLastAddress']);

    Route::post('/checkout/order', [OrderController::class, 'store']);

    // API route to check product preorder status
    Route::get('/api/product/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
       
    // Return Lagos locations as JSON
    Route::get('/api/lagos-locations', [LagosLocationController::class, 'index']);

    // Order confirmation page
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])
        ->name('orders.confirmation');

    // Paystack payment callback
    Route::get('/payment/callback', [OrderController::class, 'paystackCallback'])->name('payment.callback');

    // Invoice download
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'downloadInvoice'])
        ->name('invoice.download');

    // Receipt download
    Route::get('/orders/{order}/receipt', [ReceiptController::class, 'downloadReceipt'])
        ->name('receipt.download');

    // Temporary test route
    Route::get('/test/paid-at/{orderId}', [OrderController::class, 'testPaidAt']);

});


/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'verified', AdminMiddleware::class])
    ->group(callback: function () {

        Route::get('/', action: [AdminController::class, 'index'])
            ->name('admin.dashboard');
        
        //Category Routes
        Route::resource('categories', CategoryController::class)->except(['show']);
        
        //Brand Routes
        Route::resource('brands', BrandController::class)->except(['show']);
       
    
        //Products Routes
        Route::resource('products', ProductController::class)->except(['show']);
        
        //States Routes
        Route::resource('states', StateController::class)->except(['show']);
        
        //Locations Routes
        Route::resource('lagos-locations', LagosShippingLocationsController::class)->except(['show']);

        // Shipping Classes Routes
        Route::resource('shipping-classes', ShippingClassController::class)->except(['show']);
        Route::post('shipping-classes/close-gaps', [ShippingClassController::class, 'closeGaps'])->name('shipping-classes.close-gaps');

        // Orders Routes
        Route::resource('orders', AdminOrdersController::class)->only(['index', 'show']);
        Route::put('orders/{order}/status', [AdminOrdersController::class, 'updateStatus'])->name('admin.orders.update-status');

        // Users Routes
        Route::resource('users', AdminUserController::class)->only(['index', 'show']);
        Route::put('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.update-role');

        // Sales data route
        Route::get('/dashboard/sales-data', [AdminController::class, 'getSalesData'])->name('admin.dashboard.sales-data');

        Route::get('/calendar', fn () =>
            view('admin.pages.calender', ['title' => 'Calendar'])
        )->name('admin.calendar');

        Route::get('/profile', [AdminProfileController::class, 'index'])
            ->name('admin.profile.index');

        Route::get('/profile/edit/{id}', [AdminProfileController::class, 'edit'])
            ->name('admin.profile.edit');        

        Route::post('/profile/update', [AdminProfileController::class, 'update'])
            ->name('admin.profile.update');

        Route::get('/form-elements', fn () =>
            view('admin.pages.form.form-elements', ['title' => 'Form Elements'])
        )->name('admin.form-elements');

        Route::get('/basic-tables', fn () =>
            view('admin.pages.tables.basic-tables', ['title' => 'Basic Tables'])
        )->name('admin.basic-tables');

        Route::get('/blank', fn () =>
            view('admin.pages.blank', ['title' => 'Blank'])
        )->name('admin.blank');

        Route::get('/error-404', fn () =>
            view('admin.pages.errors.error-404', ['title' => 'Error 404'])
        )->name('admin.error-404');

        Route::get('/line-chart', fn () =>
            view('admin.pages.chart.line-chart', ['title' => 'Line Chart'])
        )->name('admin.line-chart');

        Route::get('/bar-chart', fn () =>
            view('admin.pages.chart.bar-chart', ['title' => 'Bar Chart'])
        )->name('admin.bar-chart');

        Route::get('/alerts', fn () =>
            view('admin.pages.ui-elements.alerts', ['title' => 'Alerts'])
        )->name('admin.alerts');

        Route::get('admin./avatars', fn () =>
            view('admin.pages.ui-elements.avatars', ['title' => 'Avatars'])
        )->name('admin.avatars');

        Route::get('/badges', fn () =>
            view('admin.pages.ui-elements.badges', ['title' => 'Badges'])
        )->name('admin.badges');

        Route::get('/buttons', fn () =>
            view('admin.pages.ui-elements.buttons', ['title' => 'Buttons'])
        )->name('admin.buttons');

        Route::get('/images', fn () =>
            view('admin.pages.ui-elements.images', ['title' => 'Images'])
        )->name('admin.images');

        Route::get('/videos', fn () =>
            view('admin.pages.ui-elements.videos', ['title' => 'Videos'])
        )->name('admin.videos');
    });

/*
|--------------------------------------------------------------------------
| User Routes (Public Frontend)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $products = \App\Models\Product::where('featured', true)->limit(8)->get();
    $categories = \App\Models\Category::withCount('products')->get();
    $brands = \App\Models\Brand::withCount('products')->get();
    $latestProducts = \App\Models\Product::orderBy('created_at', 'desc')->limit(5)->get();
    $latestProductsData = $latestProducts->map(fn($p) => [
    'name' => $p->name,
    'short_description' => $p->short_description,
    'image' => asset('uploads/products/' . $p->image), // make sure image path is full URL
    'link' => route('product.show', $p->slug),
]);

    //$adminUser = User::whereHas('roles', fn ($q) => $q->where('roles.id', 1))->first();
    return view('user.pages.home', [
        'title' => 'Home', 
        'products' => $products, 
        'categories' => $categories, 
        'brands' => $brands,
        'latestProducts' => $latestProducts,
        'latestProductsData' => $latestProductsData,
      
    ]);
})->name('home');

Route::get('/shop', function () {
    $products = \App\Models\Product::paginate(12);
    $categories = \App\Models\Category::all();
    $brands = \App\Models\Brand::all();
    return view('user.pages.shop', ['title' => 'Shop', 'products' => $products, 'categories' => $categories, 'brands' => $brands]);
})->name('shop');

Route::get('/product/{slug}', function ($slug) {
    $product = \App\Models\Product::where('slug', $slug)->firstOrFail();
    $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
        ->where('slug', '!=', $product->slug)
        ->limit(8)
        ->get();
    return view('user.pages.show', ['title' => $product->name, 'product' => $product, 'relatedProducts' => $relatedProducts]);
})->name('product.show');

Route::get('/cart', function () {
    return view('user.pages.cart', ['title' => 'Cart']);
})->name('cart');

Route::get('/profile', function () {
    $user = auth()->user();
    $orders = $user->orders()->latest()->take(5)->get(); // Get latest 5 orders
    $defaultAddress = $user->addresses()->default()->first(); // Get only the default address
    
    return view('user.pages.profile', [
        'title' => 'Profile',
        'user' => $user,
        'orders' => $orders,
        'defaultAddress' => $defaultAddress
    ]);
})->middleware('auth')->name('profile');

// Update profile route
Route::patch('/profile', [UserProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// User profile sub-pages
Route::get('/profile/orders', function () {
    $user = auth()->user();
    $orders = $user->orders()->latest()->paginate(10);
    
    return view('user.pages.orders', [
        'title' => 'My Orders',
        'orders' => $orders
    ]);
})->middleware('auth')->name('user.orders');

Route::get('/profile/addresses', function () {
    $user = auth()->user();
    $defaultAddress = $user->addresses()->default()->first();
    $otherAddresses = $user->addresses()->nonDefault()->get();
    
    return view('user.pages.addresses', [
        'title' => 'My Addresses',
        'defaultAddress' => $defaultAddress,
        'otherAddresses' => $otherAddresses
    ]);
})->middleware('auth')->name('user.addresses');

// Address management routes
Route::prefix('addresses')->middleware('auth')->group(function() {
    Route::patch('/{id}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::delete('/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::get('/{id}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
});

Route::get('/profile/wishlist', function () {
    // For now, just pass the title - we'll implement wishlist functionality later
    return view('user.pages.wishlist', [
        'title' => 'My Wishlist'
    ]);
})->middleware('auth')->name('user.wishlist');

Route::get('/profile/reviews', function () {
    // For now, just pass the title - we'll implement reviews functionality later
    $user = auth()->user();
    $reviews = collect([]); // Placeholder - would fetch user reviews in the future
    
    return view('user.pages.reviews', [
        'title' => 'My Reviews',
        'reviews' => $reviews
    ]);
})->middleware('auth')->name('user.reviews');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

    Route::get('/test-gd', function() {
    return extension_loaded('gd') ? 'GD Loaded' : 'GD NOT Loaded';
});


// // Order creation
// Route::post('/orders', [OrderController::class, 'store'])->middleware('auth');

// // Confirmation page
// Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])
//     ->middleware('auth');