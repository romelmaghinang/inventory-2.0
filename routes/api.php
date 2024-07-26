<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesOrderItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'login']);
Route::get('/permissions', [UserController::class, 'getUserPermissions']);

Route::middleware('auth:api')->group(function () {
    Route::apiResources(
        [
            'sales-order' => SalesOrderController::class,
            'product' => ProductController::class,
            'customer' => CustomerController::class,
            'part' => PartController::class,
            'so-item' => SalesOrderItemController::class,
        ]
    ); 
});

Route::middleware(['auth:sanctum', 'abilities:create-users,create-permission,create-role,assign-role,assign-permission'])->group(function () {
    Route::post('/create-user', [UserController::class, 'createUser']);
    Route::post('/create-role', [UserController::class, 'createRole']);
    Route::post('/assign-role', [UserController::class, 'assignRole']);
    Route::post('/assign-permission', [UserController::class, 'assignPermission']);
    Route::post('/create-permission', [UserController::class, 'createPermission']);

});


