<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order:order_number}', [OrderController::class, 'update'])->name('orders.update');

        Route::get('users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('users/create', [AdminUserController::class, 'create'])
            ->name('users.create');

        Route::post('users', [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::get('product/trashed', [ProductController::class, 'trashed'])
            ->name('product.trashed');

        Route::post('product/{id}/restore', [ProductController::class, 'restore'])
            ->name('product.restore');

        Route::delete('product/{id}/force-delete', [ProductController::class, 'forceDelete'])
            ->name('product.force-delete');

        // IMAGE (FIXED - THIS WAS MISSING EARLIER)
        Route::post('product/{id}/image/add', [ProductController::class, 'addImage'])
            ->name('product.image.add');

        Route::delete('product/image/{id}', [ProductController::class, 'deleteImage'])
            ->name('product.image.delete');

        /*
    |-----------------------
    | ATTRIBUTE (PRODUCT)
    |-----------------------
    */

        Route::post('product/{product}/attribute', [ProductController::class, 'addAttribute'])
            ->name('product.attribute.add');

        Route::delete('product/{product}/attribute/{attribute}', [ProductController::class, 'deleteAttribute'])
            ->name('product.attribute.delete');

        Route::resource('product', ProductController::class);

        /*
        |-----------------------
        | CATEGORY
        |-----------------------
        */

        Route::get('category/trashed', [CategoryController::class, 'trashed'])
            ->name('category.trashed');

        Route::post('category/{id}/restore', [CategoryController::class, 'restore'])
            ->name('category.restore');

        Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])
            ->name('category.force-delete');

        Route::resource('category', CategoryController::class);

        /*
        |-----------------------
        | ATTRIBUTE
        |-----------------------
        */

        Route::get('attribute/trashed', [AttributeController::class, 'trashed'])
            ->name('attribute.trashed');

        Route::post('attribute/{id}/restore', [AttributeController::class, 'restore'])
            ->name('attribute.restore');

        Route::delete('attribute/{id}/force-delete', [AttributeController::class, 'forceDelete'])
            ->name('attribute.force-delete');

        Route::resource('attribute', AttributeController::class);
    });

Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/listing/{slug}', [HomeController::class, 'listing'])->name('listing');
    Route::get('/details/{slug}', [HomeController::class, 'details'])->name('details');
    Route::post('/add-to-cart', [CartController::class, 'store'])->name('add_to_cart');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
});

Route::name('payments.')->group(function () {
    Route::get('/payment/esewa/success', [CheckoutController::class, 'esewaSuccess'])->name('esewa.success');
    Route::get('/payment/khalti/success/{order:order_number}', [CheckoutController::class, 'khaltiSuccess'])->name('khalti.success');
    Route::get('/payment/paypal/success/{order:order_number}', [CheckoutController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/payment/stripe/success/{order:order_number}', [CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/payment/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/payment/failure', [CheckoutController::class, 'failure'])->name('failure');
});

/*
|--------------------------------------------------------------------------
| AUTH FILES
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->group(function () {
    // Guest Routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])
            ->name('customer.register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('customer.login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
            ->name('customer.password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
            ->name('customer.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
            ->name('customer.password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->name('customer.password.update');
    });
    // Authenticated Routes
    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])
            ->name('customer.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('customer.logout');
        Route::get('/wishlist', [WishlistController::class, 'index'])
            ->name('customer.wishlist');
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])
            ->name('customer.wishlist.toggle');
        Route::post('/reviews/{product}', [ReviewController::class, 'store'])
            ->name('customer.reviews.store');
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('customer.checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('customer.checkout.store');
    });
});
require __DIR__.'/auth.php';
