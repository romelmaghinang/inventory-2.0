<?php

use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CarrierServiceController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\SalesOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'login']);
Route::get('/permissions', [UserController::class, 'getUserPermissions']);
Route::post('/sales-orders', [SalesOrderController::class, 'create']);
Route::post('/carrier', [CarrierController::class, 'getCarrierId']);
Route::post('/carrierService', [CarrierServiceController::class, 'getCarrierServiceId']);
Route::post('/currency', [CurrencyController::class, 'getCurrency']);
Route::post('/location', [LocationController::class, 'getLocationGroup']);
Route::post('priority/get-id', [PriorityController::class, 'getPriorityId']);


Route::middleware(['auth:sanctum', 'abilities:create-users,create-permission,create-role,assign-role,assign-permission'])->group(function () {
    Route::post('/create-user', [UserController::class, 'createUser']);
    Route::post('/create-role', [UserController::class, 'createRole']);
    Route::post('/assign-role', [UserController::class, 'assignRole']);
    Route::post('/assign-permission', [UserController::class, 'assignPermission']);
    Route::post('/create-permission', [UserController::class, 'createPermission']);
});


