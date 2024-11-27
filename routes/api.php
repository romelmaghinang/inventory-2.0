<?php

use App\Http\Controllers\CountryAndStateController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PaymentTermsController;
use App\Http\Controllers\PickController;
use App\Http\Controllers\PickStatus\FinishController;
use App\Http\Controllers\PickStatus\StartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuickBookClassController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ShipStatus\PackController;
use App\Http\Controllers\ShipStatus\ShipController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\UnitOfMeasureController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ReceiptStatus\ReconciledController;
use App\Http\Controllers\ReceiptStatus\FulfilledController;
use App\Http\Controllers\ReceiptStatus\VoidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferOrderController;

Route::post('/register', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/permissions', [UserController::class, 'getUserPermissions']);

Route::middleware(['auth:sanctum', ])->group(function () {
    Route::post('/transfer-orders', [TransferOrderController::class, 'store'])->middleware('abilities:create-transfer-order');
    Route::put('/transfer-orders/update/{id}', [TransferOrderController::class, 'update'])->middleware('abilities:update-transfer-order');
    Route::post('transfer-orders/update-status/{statusName}', [TransferOrderController::class, 'updateStatus'])->middleware('abilities:update-status-transfer-order');
    Route::delete('/transfer-orders/void', [TransferOrderController::class, 'deleteXo'])->middleware('abilities:void-transfer-order');
    Route::delete('/transfer-orders/delete', [TransferOrderController::class, 'deleteXoItem'])->middleware('abilities:delete-transfer-order');
    Route::get('/transfer-orders/xo', [TransferOrderController::class, 'showXo'])->middleware('abilities:show-transfer-order');
    Route::get('transfer-orders/xoitem', [TransferOrderController::class, 'showXoItem'])->middleware('abilities:item-show-transfer-order');

    Route::prefix('qbclass')->group(function () {
        
        Route::post('/', [QuickBookClassController::class, 'store'])->middleware('abilities:create-qbclass');
        Route::put('/', [QuickBookClassController::class, 'update'])->middleware('abilities:update-qbclass');
        Route::get('/', [QuickBookClassController::class, 'show'])->middleware('abilities:view-qbclass');
        Route::delete('/', [QuickBookClassController::class, 'destroy'])->middleware('abilities:delete-qbclass');
    });

    Route::prefix('taxrate')->group(function () {
        Route::post('/', [TaxRateController::class, 'store'])->middleware('abilities:create-taxrate');
        Route::put('/', [TaxRateController::class, 'update'])->middleware('abilities:update-taxrate');
        Route::get('/', [TaxRateController::class, 'show'])->middleware('abilities:view-taxrate');
        Route::delete('/', [TaxRateController::class, 'destroy'])->middleware('abilities:delete-taxrate');
    });

    Route::prefix('payment-terms')->group(function () {
        Route::post('/', [PaymentTermsController::class, 'store'])->middleware('abilities:create-payment-terms');
        Route::put('/{id}', [PaymentTermsController::class, 'update'])->middleware('abilities:update-payment-terms');
        Route::get('/', [PaymentTermsController::class, 'show'])->middleware('abilities:view-payment-terms');
        Route::delete('/', [PaymentTermsController::class, 'destroy'])->middleware('abilities:delete-payment-terms');
    });

    Route::prefix('currency')->group(function () {
        Route::post('/', [CurrencyController::class, 'store'])->middleware('abilities:create-currency');
        Route::put('/', [CurrencyController::class, 'update'])->middleware('abilities:update-currency');
        Route::get('/', [CurrencyController::class, 'show'])->middleware('abilities:view-currency');
        Route::delete('/', [CurrencyController::class, 'destroy'])->middleware('abilities:delete-currency');
     });

    Route::prefix('vendor')->group(function () {
        Route::post('/', [VendorController::class, 'store'])->middleware('abilities:create-vendor');
        Route::put('/{id}', [VendorController::class, 'update'])->middleware('abilities:update-vendor');
        Route::get('/', [VendorController::class, 'show'])->middleware('abilities:view-vendor');
        Route::delete('/', [VendorController::class, 'destroy'])->middleware('abilities:delete-vendor');
    });

    Route::prefix('part')->group(function () {
        Route::post('/', [PartController::class, 'store'])->middleware('abilities:create-part');
        Route::put('/{id}', [PartController::class, 'update'])->middleware('abilities:update-part');
        Route::get('/', [PartController::class, 'show'])->middleware('abilities:view-part');
        Route::delete('/', [PartController::class, 'destroy'])->middleware('abilities:delete-part');
    });

    Route::prefix('purchase-order')->group(function () {
        Route::post('/', [PurchaseOrderController::class, 'store'])->middleware('abilities:create-purchase-order');
        Route::put('/{id}', [PurchaseOrderController::class, 'update'])->middleware('abilities:update-purchase-order');
        Route::get('/', [PurchaseOrderController::class, 'show'])->middleware('abilities:view-purchase-order');
        Route::delete('/', [PurchaseOrderController::class, 'destroy'])->middleware('abilities:delete-purchase-order');
    });

    Route::prefix('product')->group(function () {
        Route::post('/', [ProductController::class, 'store'])->middleware('abilities:create-product');
        Route::put('/{id}', [ProductController::class, 'update'])->middleware('abilities:update-product');
        Route::get('/', [ProductController::class, 'show'])->middleware('abilities:view-product');
        Route::delete('/', [ProductController::class, 'destroy'])->middleware('abilities:delete-product');
    });

    Route::prefix('sales-order')->group(function () {
        Route::post('/', [SalesOrderController::class, 'store'])->middleware('abilities:create-sales-order');
        Route::put('/', [SalesOrderController::class, 'update'])->middleware('abilities:update-sales-order');
        Route::get('/', [SalesOrderController::class, 'show'])->middleware('abilities:view-sales-order');
        Route::delete('/', [SalesOrderController::class, 'destroy'])->middleware('abilities:delete-sales-order');
    });

    Route::prefix('pick')->group(function () {
        Route::post('/', [PickController::class, 'store'])->middleware('abilities:create-pick');
        Route::put('/{id}', [PickController::class, 'update'])->middleware('abilities:update-pick');
        Route::get('/', [PickController::class, 'show'])->middleware('abilities:view-pick');
        Route::delete('/', [PickController::class, 'destroy'])->middleware('abilities:delete-pick');
    });

    Route::prefix('location')->group(function () {
        Route::post('/', [LocationController::class, 'store'])->middleware('abilities:create-location');
        Route::put('/{id}', [LocationController::class, 'update'])->middleware('abilities:update-location');
        Route::get('/', [LocationController::class, 'show'])->middleware('abilities:view-location');
        Route::delete('/', [LocationController::class, 'destroy'])->middleware('abilities:delete-location');
    });

    Route::prefix('state')->group(function () {
        Route::post('/', [CountryAndStateController::class, 'storeState'])->middleware('abilities:create-state');
        Route::get('/', [CountryAndStateController::class, 'showState'])->middleware('abilities:view-state');
        Route::put('/{id}', [CountryAndStateController::class, 'updateState'])->middleware('abilities:update-state');
        Route::delete('/', [CountryAndStateController::class, 'deleteState'])->middleware('abilities:delete-state');
    });

    Route::prefix('country')->group(function () {
        Route::get('/', [CountryAndStateController::class, 'showCountry'])->middleware('abilities:view-country');
    });

    Route::prefix('customer')->group(function () {
        Route::post('/', [CustomerController::class, 'store'])->middleware('abilities:create-customer');
        Route::get('/', [CustomerController::class, 'showAll'])->middleware('abilities:view-customer');
        Route::get('/', [CustomerController::class, 'show'])->middleware('abilities:view-customer');
        Route::get('/', [CustomerController::class, 'showFilter'])->middleware('abilities:view-customer');
        Route::put('/{id}', [CustomerController::class, 'update'])->middleware('abilities:update-customer');
        Route::delete('/', [CustomerController::class, 'destroy'])->middleware('abilities:delete-customer');
    });
    Route::post('/create-role', [UserController::class, 'createRole']);
    Route::post('/assign-role', [UserController::class, 'assignRole']);
    Route::post('/assign-permission', [UserController::class, 'assignPermission']);
    Route::post('/create-permission', [UserController::class, 'createPermission']);
    Route::post('pick-start', [StartController::class, 'store'])->middleware('abilities:pick-start');
    Route::post('pick-finish', [FinishController::class, 'store'])->middleware('abilities:pick-finish');
    Route::post('receipt-reconciled', [ReconciledController::class, 'store'])->middleware('abilities:receipt-reconciled');
    Route::post('receipt-fulfilled', [FulfilledController::class, 'store'])->middleware('abilities:receipt-fulfilled');
    Route::post('pack', [PackController::class, 'store'])->middleware('abilities:pack');
    Route::post('ship', [ShipController::class, 'store'])->middleware('abilities:ship');
    Route::post('inventory', [InventoryController::class, 'store'])->middleware('abilities:inventory');
    Route::get('inventory', [InventoryController::class, 'showInventories'])->middleware('abilities:inventory');
    Route::post('/receiving', [ReceivingController::class, 'receiving'])->middleware('abilities:receiving');
    Route::get('/receiving', [ReceivingController::class, 'show'])->middleware('abilities:receiving');
    Route::delete('receipt-void', [ReceivingController::class, 'delete'])->middleware('abilities:receipt-void');
});
