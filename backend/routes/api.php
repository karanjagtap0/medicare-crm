<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::middleware(['auth:sanctum', 'role:Super Admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Categories
    Route::get('/categories', [\App\Http\Controllers\Master\CategoryController::class, 'index']);
    Route::post('/categories', [\App\Http\Controllers\Master\CategoryController::class, 'store']);
    Route::get('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'show']);
    Route::put('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'update']);
    Route::patch('/categories/{id}/status', [\App\Http\Controllers\Master\CategoryController::class, 'updateStatus']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Master\CategoryController::class, 'destroy']);

    // Brands
    Route::get('/brands', [\App\Http\Controllers\Master\BrandController::class, 'index']);
    Route::post('/brands', [\App\Http\Controllers\Master\BrandController::class, 'store']);
    Route::get('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'show']);
    Route::put('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'update']);
    Route::patch('/brands/{id}/status', [\App\Http\Controllers\Master\BrandController::class, 'updateStatus']);
    Route::delete('/brands/{id}', [\App\Http\Controllers\Master\BrandController::class, 'destroy']);

    // Suppliers
    Route::get('/suppliers', [\App\Http\Controllers\Master\SupplierController::class, 'index']);
    Route::post('/suppliers', [\App\Http\Controllers\Master\SupplierController::class, 'store']);
    Route::get('/suppliers/{id}', [\App\Http\Controllers\Master\SupplierController::class, 'show']);
    Route::put('/suppliers/{id}', [\App\Http\Controllers\Master\SupplierController::class, 'update']);
    Route::patch('/suppliers/{id}/status', [\App\Http\Controllers\Master\SupplierController::class, 'updateStatus']);
    Route::delete('/suppliers/{id}', [\App\Http\Controllers\Master\SupplierController::class, 'destroy']);

    // Taxes
    Route::get('/taxes', [\App\Http\Controllers\Master\TaxController::class, 'index']);
    Route::post('/taxes', [\App\Http\Controllers\Master\TaxController::class, 'store']);
    Route::get('/taxes/{id}', [\App\Http\Controllers\Master\TaxController::class, 'show']);
    Route::put('/taxes/{id}', [\App\Http\Controllers\Master\TaxController::class, 'update']);
    Route::patch('/taxes/{id}/status', [\App\Http\Controllers\Master\TaxController::class, 'updateStatus']);
    Route::delete('/taxes/{id}', [\App\Http\Controllers\Master\TaxController::class, 'destroy']);

    // UOMs
    Route::get('/uoms', [\App\Http\Controllers\Master\UomController::class, 'index']);
    Route::post('/uoms', [\App\Http\Controllers\Master\UomController::class, 'store']);
    Route::get('/uoms/{id}', [\App\Http\Controllers\Master\UomController::class, 'show']);
    Route::put('/uoms/{id}', [\App\Http\Controllers\Master\UomController::class, 'update']);
    Route::patch('/uoms/{id}/status', [\App\Http\Controllers\Master\UomController::class, 'updateStatus']);
    Route::delete('/uoms/{id}', [\App\Http\Controllers\Master\UomController::class, 'destroy']);

    // Medicines
    Route::get('/medicines', [\App\Http\Controllers\MedicineController::class, 'index']);
    Route::post('/medicines', [\App\Http\Controllers\MedicineController::class, 'store']);
    Route::get('/medicines/{id}', [\App\Http\Controllers\MedicineController::class, 'show']);
    Route::put('/medicines/{id}', [\App\Http\Controllers\MedicineController::class, 'update']);
    Route::patch('/medicines/{id}/status', [\App\Http\Controllers\MedicineController::class, 'updateStatus']);
    Route::delete('/medicines/{id}', [\App\Http\Controllers\MedicineController::class, 'destroy']);

    // Medicine Batches
    Route::get('/medicine-batches', [\App\Http\Controllers\MedicineBatchController::class, 'index']);
    Route::post('/medicine-batches', [\App\Http\Controllers\MedicineBatchController::class, 'store']);
    Route::get('/medicine-batches/{id}', [\App\Http\Controllers\MedicineBatchController::class, 'show']);
    Route::put('/medicine-batches/{id}', [\App\Http\Controllers\MedicineBatchController::class, 'update']);
    Route::delete('/medicine-batches/{id}', [\App\Http\Controllers\MedicineBatchController::class, 'destroy']);

    // Expiry Management
    Route::get('/expiry/dashboard', [\App\Http\Controllers\ExpiryController::class, 'dashboard']);
    Route::get('/expiry/expired', [\App\Http\Controllers\ExpiryController::class, 'expired']);
    Route::get('/expiry/expiring', [\App\Http\Controllers\ExpiryController::class, 'expiring']);
    Route::get('/expiry/{batch}', [\App\Http\Controllers\ExpiryController::class, 'show']);
    Route::patch('/expiry/{batch}/block', [\App\Http\Controllers\ExpiryController::class, 'block']);

    // Stock Management
    Route::get('/stocks', [\App\Http\Controllers\StockController::class, 'index']);
    Route::get('/stocks/history', [\App\Http\Controllers\StockController::class, 'history']);
    Route::get('/stocks/low-stock', [\App\Http\Controllers\StockController::class, 'lowStock']);
    Route::get('/stocks/dashboard', [\App\Http\Controllers\StockController::class, 'dashboard']);
    Route::post('/stocks/in', [\App\Http\Controllers\StockController::class, 'stockIn']);
    Route::post('/stocks/out', [\App\Http\Controllers\StockController::class, 'stockOut']);
    Route::post('/stocks/adjustment', [\App\Http\Controllers\StockController::class, 'adjustment']);
    Route::post('/stocks/transfer', [\App\Http\Controllers\StockController::class, 'transfer']);
    Route::get('/stocks/{medicine}', [\App\Http\Controllers\StockController::class, 'medicineStock']);

    // Barcode Management
    Route::post('/barcodes/generate', [\App\Http\Controllers\BarcodeController::class, 'generate']);
    Route::get('/barcodes/search', [\App\Http\Controllers\BarcodeController::class, 'search']);
    Route::get('/barcodes/{batch}', [\App\Http\Controllers\BarcodeController::class, 'show']);
    Route::get('/barcodes/{batch}/print', [\App\Http\Controllers\BarcodeController::class, 'print']);
    Route::get('/barcodes/{batch}/download', [\App\Http\Controllers\BarcodeController::class, 'download']);

    // Medicine Images
    Route::patch('/medicines/images/reorder', [\App\Http\Controllers\MedicineImageController::class, 'reorder']);
    Route::get('/medicines/{medicine}/images', [\App\Http\Controllers\MedicineImageController::class, 'index']);
    Route::post('/medicines/{medicine}/images', [\App\Http\Controllers\MedicineImageController::class, 'store']);
    Route::get('/medicines/images/{image}', [\App\Http\Controllers\MedicineImageController::class, 'show']);
    Route::put('/medicines/images/{image}', [\App\Http\Controllers\MedicineImageController::class, 'update']);
    Route::patch('/medicines/images/{image}/primary', [\App\Http\Controllers\MedicineImageController::class, 'setPrimary']);
    Route::delete('/medicines/images/{image}', [\App\Http\Controllers\MedicineImageController::class, 'destroy']);
    // Customers
    Route::get('/customers', [\App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::post('/customers', [\App\Http\Controllers\Api\CustomerController::class, 'store']);
    Route::get('/customers/{id}', [\App\Http\Controllers\Api\CustomerController::class, 'show']);
    Route::put('/customers/{id}', [\App\Http\Controllers\Api\CustomerController::class, 'update']);
    Route::patch('/customers/{id}/status', [\App\Http\Controllers\Api\CustomerController::class, 'updateStatus']);
    Route::delete('/customers/{id}', [\App\Http\Controllers\Api\CustomerController::class, 'destroy']);

    // Customer Addresses
    Route::get('/customers/{id}/addresses', [\App\Http\Controllers\Api\CustomerAddressController::class, 'index']);
    Route::post('/customers/{id}/addresses', [\App\Http\Controllers\Api\CustomerAddressController::class, 'store']);
    Route::put('/customer-addresses/{id}', [\App\Http\Controllers\Api\CustomerAddressController::class, 'update']);
    Route::delete('/customer-addresses/{id}', [\App\Http\Controllers\Api\CustomerAddressController::class, 'destroy']);

    // Cart
    Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('/cart/items', [\App\Http\Controllers\Api\CartController::class, 'store']);
    Route::put('/cart/items/{id}', [\App\Http\Controllers\Api\CartController::class, 'update']);
    Route::delete('/cart/items/{id}', [\App\Http\Controllers\Api\CartController::class, 'destroy']);
    Route::delete('/cart', [\App\Http\Controllers\Api\CartController::class, 'clear']);
    Route::get('/cart/summary', [\App\Http\Controllers\Api\CartController::class, 'summary']);
});
