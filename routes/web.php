<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\OrderController as PegawaiOrderController;
use App\Http\Controllers\Pegawai\PaymentController as PegawaiPaymentController;
use App\Http\Controllers\User\AddressController as UserAddressController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\User\CheckoutController as UserCheckoutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProducts = Product::with(['category', 'images'])->where('is_active', true)->latest()->take(8)->get();
    $categories = Category::withCount(['products' => fn($q) => $q->where('is_active', true)])->where('is_active', true)->get();

    return view('welcome', compact('featuredProducts', 'categories'));
});

// Public route and logic
Route::get('/products', function (Request $request) {
    $query = Product::with(['category', 'images'])
        ->where('is_active', true);

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where(
                'name',
                'like',
                "%{$search}%"
            );

            $q->orWhere(
                'description',
                'like',
                "%{$search}%"
            );
        });
    }

    if ($request->filled('category')) {
        $query->where(
            'category_id',
            $request->integer('category')
        );
    }

    $products = $query
        ->latest()
        ->paginate(12)
        ->withQueryString();

    $categories = Category::where('is_active', true)->get();

    return view(
        'products.index',
        compact('products', 'categories')
    );
})->name('products.index');

Route::get('/products/{product:slug}', function (Product $product) {
    abort_unless($product->is_active, 404);
    $product->load(['category', 'images']);
    $relatedProducts = Product::with(['category', 'images'])
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->take(4)
        ->get();

    return view('products.show', compact('product', 'relatedProducts'));
})->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
});

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [PegawaiOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::resource('users', AdminUserController::class);
});

// Route Pegawai
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [PegawaiOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [PegawaiOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [PegawaiOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/payments', [PegawaiPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/verify', [PegawaiPaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [PegawaiPaymentController::class, 'reject'])->name('payments.reject');
});

// Route User
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/addresses', [UserAddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [UserAddressController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}', [UserAddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [UserAddressController::class, 'destroy'])->name('addresses.destroy');
    Route::get('/cart', [UserCartController::class, 'index'])->name('cart');
    Route::post('/cart/{product}', [UserCartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [UserCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [UserCartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [UserCheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [UserCheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment', [UserPaymentController::class, 'show'])->name('payment.show');
    Route::post('/orders/{order}/payment/proof', [UserPaymentController::class, 'uploadProof'])->name('payment.proof');
});
