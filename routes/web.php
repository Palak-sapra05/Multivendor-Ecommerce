<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\SubOrder;
use App\Models\Store;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
Route::get('/', function (\Illuminate\Http\Request $request) {
    $query = Product::with('store')->where('is_active', true);

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    $products = $query->get();
    return view('welcome', compact('products'));
});

Route::get('/product/{id}', function ($id) {
    $product = Product::with('store')->findOrFail($id);
    return view('product.show', compact('product'));
})->name('product.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart View (Public)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Wishlist Routes
Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [\App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

// Checkout Routes (Requires Authentication to access customer checkout)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.form');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

    // Cart Actions (Requires Auth)
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Payment
    Route::get('/payment/gateway/{order}', [\App\Http\Controllers\PaymentController::class, 'gateway'])->name('payment.gateway');
    Route::post('/payment/process/{order}', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');

    // Order History & Management
    Route::get('/orders', [\App\Http\Controllers\OrderHistoryController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\OrderHistoryController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [\App\Http\Controllers\OrderHistoryController::class, 'updateStatus'])->name('orders.status.update');

    // Chat Routes
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start', [\App\Http\Controllers\ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{conversation}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');

    // Settings Routes
    Route::get('/set-locale/{locale}', [\App\Http\Controllers\SettingsController::class, 'setLocale'])->name('set.locale');
    Route::get('/set-currency/{currency}', [\App\Http\Controllers\SettingsController::class, 'setCurrency'])->name('set.currency');
    Route::get('/tracking/{tracking_number}', [\App\Http\Controllers\TrackingController::class, 'show'])->name('tracking.show');

    // Review Routes
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Dashboard logic requires custom middlewares in real app; simulating check
Route::middleware('auth')->group(function () {
    // Vendor Registration
    Route::get('/seller/register', [\App\Http\Controllers\VendorController::class, 'showRegister'])->name('seller.register');
    Route::post('/seller/register', [\App\Http\Controllers\VendorController::class, 'register'])->name('seller.register.submit');

    Route::get('/dashboard/seller', function () {
        if (auth()->user()->role !== 'seller' && auth()->user()->role !== 'admin')
            abort(403);
        $store = auth()->user()->stores()->first();
        
        if (!$store) {
            return redirect()->route('seller.register');
        }

        $earnings = SubOrder::where('store_id', $store->id)->sum('seller_earning');
        $orders = SubOrder::with('items')->where('store_id', $store->id)->orderBy('created_at', 'desc')->get();
        return view('dashboard.seller', compact('store', 'earnings', 'orders'));
    })->name('seller.dashboard');

    Route::get('/dashboard/seller/products/create', function () {
        if (auth()->user()->role !== 'seller' && auth()->user()->role !== 'admin')
            abort(403);
        $store = auth()->user()->stores()->first();
        if (!$store) {
            return redirect()->route('seller.dashboard')->with('error', 'You must have a store to add products.');
        }
        return view('product.create', compact('store'));
    })->name('seller.products.create');

    Route::post('/dashboard/seller/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('seller.products.store');
    Route::get('/dashboard/seller/products/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/dashboard/seller/products/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/dashboard/seller/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('seller.products.destroy');

    Route::post('/dashboard/seller/orders/{id}/status', function (\Illuminate\Http\Request $request, $id) {
        if (auth()->user()->role !== 'seller' && auth()->user()->role !== 'admin')
            abort(403);
        $store = auth()->user()->stores()->first();
        $subOrder = SubOrder::where('store_id', $store->id)->findOrFail($id);

        $request->validate(['status' => 'required|in:pending,shipped,delivered']);
        $subOrder->status = $request->status;
        $subOrder->save();

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status));
    })->name('seller.orders.status');

    // Admin Store Approval Routes
    Route::post('/admin/stores/{id}/approve', function ($id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $store = Store::findOrFail($id);
        $store->update(['is_approved' => true, 'status' => 'active']);
        return back()->with('success', 'Store approved successfully!');
    })->name('admin.stores.approve');

    Route::post('/admin/stores/{id}/reject', function ($id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $store = Store::findOrFail($id);
        $store->update(['is_approved' => false, 'status' => 'suspended']);
        return back()->with('error', 'Store application rejected.');
    })->name('admin.stores.reject');

    Route::get('/dashboard/admin', function () {
        if (auth()->user()->role !== 'admin')
            abort(403);
        $totalCommission = SubOrder::sum('commission_amount');
        $totalOrders = SubOrder::count();
        $totalStores = Store::count();
        $allSubOrders = SubOrder::with('store')->orderBy('created_at', 'desc')->get();
        $pendingStores = Store::where('is_approved', false)->get();
        return view('dashboard.admin', compact('totalCommission', 'totalOrders', 'totalStores', 'allSubOrders', 'pendingStores'));
    })->name('admin.dashboard');

    Route::get('/api/search/suggestions', function (\Illuminate\Http\Request $request) {
        $q = $request->input('q');
        if (empty($q)) return response()->json([]);
        
        $products = Product::where('name', 'like', "%{$q}%")
            ->where('is_active', true)
            ->limit(8)
            ->get(['id', 'name', 'images', 'price']);
            
        return response()->json($products);
    })->name('api.search.suggestions');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate(
        [
            'email' => $googleUser->email,
        ],
        [
            'name' => $googleUser->name,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
        ]
    );

    Auth::login($user);

    return redirect('/');

});