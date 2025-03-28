<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---

// CATEGORIES
Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('api.categories.show');

// PRODUCTS
Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('api.products.show');


// --- AUTHENTICATION ROUTE ---
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('api-token-'. $user->id)->plainTextToken;
        return response()->json(['token' => $token]);
    }

    return response()->json(['message' => 'Invalid credentials'], 401);
})->name('api.login');


// --- PROTECTED ROUTES ---
Route::middleware('auth:sanctum')->group(function () {

    // CATEGORIES (Protected)
    Route::post('/categories', [CategoryController::class, 'store'])->name('api.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('api.categories.update');
    // Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('api.categories.update'); // Patch, Will need later maybe
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('api.categories.destroy');
    Route::get('/products/{product}/categories', [CategoryController::class, 'getCategoriesForProduct'])->name('api.products.categories');

    // PRODUCTS (Protected)
    Route::post('/products', [ProductController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');
    // Route::patch('/products/{product}', [ProductController::class, 'update'])->name('api.products.update'); // Patch, Will need later maybe
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');


    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    })->name('api.logout');

});
