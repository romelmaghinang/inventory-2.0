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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [UserController::class, 'login']);
Route::get('/permissions', [UserController::class, 'getUserPermissions']);

Route::middleware('auth:api')->group(function () {
});
    Route::apiResources(
            [
                'pick' => PickController::class,
                'sales-order' => SalesOrderController::class,
                'purchase-order' => PurchaseOrderController::class,
                'product' => ProductController::class,
                'customer' => CustomerController::class,
                'location' => LocationController::class,
                'part' => PartController::class,
                'vendor' => VendorController::class,
                'country-state' => CountryAndStateController::class,
                'qbclass' => QuickBookClassController::class,
                'taxrate' => TaxRateController::class,
                'currency' => CurrencyController::class,
                'payment-terms' => PaymentTermsController::class,
            ]
        ); 
        Route::post('/create-role', [UserController::class, 'createRole']);
            Route::post('/assign-role', [UserController::class, 'assignRole']);
            Route::post('/assign-permission', [UserController::class, 'assignPermission']);
            Route::post('/create-permission', [UserController::class, 'createPermission']);
            Route::post('pick-start', StartController::class);
            Route::post('pick-finish', FinishController::class);
            Route::post('receipt-reconciled', ReconciledController::class);
            Route::post('receipt-fulfilled', FulfilledController::class);
            Route::post('pack', PackController::class);
            Route::post('ship', ShipController::class);
            Route::post('inventory', [InventoryController::class, 'store']);
            Route::post('/receiving', [ReceivingController::class, 'receiving']);


        Route::middleware(['auth:sanctum', 'abilities:create-users,create-permission,create-role,assign-role,assign-permission,pick-finish,pick-start,pack,ship,inventory'])->group(function () {
     
        });
        