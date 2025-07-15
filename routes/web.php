<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/category/{slug}', [HomeController::class, 'categoryProducts'])->name('category.products');
Route::get('/brand/{slug}', [HomeController::class, 'brandProducts'])->name('brand.products');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Checkout Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('index');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/address', [App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('address.store');
        Route::put('/address/{address}', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('address.update');
        Route::delete('/address/{address}', [App\Http\Controllers\ProfileController::class, 'deleteAddress'])->name('address.delete');
    });

    // User Orders Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
        Route::get('/{order}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('invoice');
        Route::post('/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/success/{order}', [CheckoutController::class, 'success'])->name('order.success');
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/razorpay/{order}', [PaymentController::class, 'razorpay'])->name('razorpay');
        Route::post('/razorpay/callback', [PaymentController::class, 'razorpayCallback'])->name('razorpay.callback');
        Route::get('/failed/{order}', [PaymentController::class, 'failed'])->name('failed');
        Route::get('/retry/{order}', [PaymentController::class, 'retry'])->name('retry');
    });
});

// Authentication Routes
Auth::routes();

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Brands
    Route::resource('brands', BrandController::class);
    
    // Products
    Route::resource('products', ProductController::class);
    Route::delete('/products/image/delete', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    
    // Orders
    Route::resource('orders', OrderController::class)->except(['create', 'store', 'edit']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::post('/orders/{order}/note', [OrderController::class, 'addNote'])->name('orders.note');
    Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    
    // Users Management
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{user}/addresses', [UserController::class, 'addresses'])->name('users.addresses');
    Route::get('/users/{user}/orders', [UserController::class, 'orders'])->name('users.orders');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
