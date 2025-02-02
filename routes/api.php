<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\PurchaseItemController;
use App\Http\Controllers\Api\SupplierLedgerController;

Route::apiResource('products', ProductController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('purchases', PurchaseController::class);
// Route::apiResource('purchases', PurchaseController::class)->only(['index', 'store']);
Route::apiResource('purchase-items', PurchaseItemController::class);
// Route::apiResource('supplier-ledgers', SupplierLedgerController::class);

// Route::get('suppliers/{supplier}/ledger', [SupplierLedgerController::class, 'show']);
Route::post('ledger/purchase', [SupplierLedgerController::class, 'addPurchaseLedgerEntry']);
Route::post('ledger/payment', [SupplierLedgerController::class, 'addPaymentLedgerEntry']);
Route::get('ledger/{supplier_id}', [SupplierLedgerController::class, 'getLedger']);
