<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StorePageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'bn'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language.update');

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->middleware('throttle:5,1')->name('register.submit');
});
Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [StorePageController::class, 'about'])->name('about');
Route::get('/farmers', [StorePageController::class, 'farmers'])->name('farmers');
Route::get('/blog', [StorePageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [StorePageController::class, 'article'])->name('blog.show');
Route::get('/contact', [StorePageController::class, 'contact'])->name('contact');
Route::post('/contact', [StorePageController::class, 'sendContact'])->middleware('throttle:5,1')->name('contact.send');
Route::get('/policies/terms', [StorePageController::class, 'terms'])->name('policies.terms');
Route::get('/policies/returns', [StorePageController::class, 'returns'])->name('policies.returns');
Route::get('/policies/support', [StorePageController::class, 'support'])->name('policies.support');
Route::get('/policies/privacy', [StorePageController::class, 'privacy'])->name('policies.privacy');
Route::get('/account/orders', [StorePageController::class, 'orders'])
    ->middleware('auth')
    ->name('account.orders');
Route::get('/shop/{slug?}', [ProductController::class, 'index'])->name('shop');
Route::post('/products/{product}/add-to-cart', [ProductController::class, 'addToCart'])->name('products.add_to_cart');
Route::post('/products/{product}/quick-buy', [ProductController::class, 'quickBuy'])->name('products.quick_buy');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
Route::get('/orders/{orderNumber}', [CartController::class, 'confirmation'])->name('orders.confirmation');
Route::get('/orders/{orderNumber}/invoice', [InvoiceController::class, 'customer'])
    ->middleware('signed')
    ->name('orders.invoice');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
        Route::get('/content', [AdminController::class, 'content'])->name('content');
        Route::put('/content', [AdminController::class, 'updateContent'])->name('content.update');
        Route::resource('/blog-posts', BlogPostController::class)->except(['show'])->names('blog-posts');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}/invoice', [InvoiceController::class, 'admin'])->name('orders.invoice');
        Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::put('/users/{user}/admin-access', [AdminController::class, 'updateAdminAccess'])->name('users.admin-access');
    });
});
