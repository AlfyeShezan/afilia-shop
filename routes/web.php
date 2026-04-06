<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

use App\Livewire\CategoryManager;
use App\Livewire\ProductManager;
use App\Livewire\ProductCatalog;
use App\Livewire\ProductDetail;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\AdminDashboard;
use App\Livewire\VendorRegistration;
use App\Livewire\VendorDashboard;
use App\Livewire\VendorWithdrawal;
use App\Livewire\OrderManager;
use App\Livewire\UserManager;
use App\Livewire\VendorManager;
use App\Livewire\AddressManager;
use App\Livewire\VoucherList;
use App\Livewire\HelpCenter;
use App\Livewire\VoucherManager;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\InvoiceController;
use App\Livewire\AboutPage;
use App\Livewire\ContactPage;
use App\Livewire\SystemSettingManager;

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::get('/', ProductCatalog::class)->name('home');
Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/wishlist', App\Livewire\WishlistPage::class)->name('wishlist')->middleware(['auth']);
Route::get('/orders', App\Livewire\OrderHistory::class)->name('order.history')->middleware(['auth']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/notifications', App\Livewire\NotificationPage::class)->name('notifications');
    Route::get('dashboard', function () {
        if (auth()->user()->hasRole('customer')) {
            return redirect()->route('order.history');
        }
        return view('dashboard');
    })->name('dashboard');
    
    // Admin Routes
    Route::middleware(['role:super-admin|admin|staff'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('categories', CategoryManager::class)->name('categories');
        Route::get('products', ProductManager::class)->name('products');
        Route::get('orders', OrderManager::class)->name('orders');
        Route::get('users', UserManager::class)->name('users');
        Route::get('vendors', VendorManager::class)->name('vendors');
        Route::get('vouchers', VoucherManager::class)->name('vouchers');
        Route::get('settings', SystemSettingManager::class)->name('settings');
    });

    // Marketplace Routes
    Route::get('/vendor/register', VendorRegistration::class)->name('vendor.register');
    
    Route::middleware(['role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('dashboard', VendorDashboard::class)->name('dashboard');
        Route::get('withdrawal', VendorWithdrawal::class)->name('withdrawal');
    });

    // Customer Account Routes
    Route::get('/addresses', AddressManager::class)->name('addresses');
    Route::get('/vouchers', VoucherList::class)->name('vouchers');
    Route::get('/invoice/{orderNumber}', [InvoiceController::class, 'show'])->name('invoice.show');
});

Route::get('/help', HelpCenter::class)->name('help');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
