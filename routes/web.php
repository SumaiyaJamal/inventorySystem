<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\WarehouseManagerController;
use App\Http\Controllers\InventoryRequestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//sumaiya
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/logout', [DashboardController::class, 'logout'])->middleware('auth')->name('logout');

// Inventory routes
Route::middleware('auth')->group(function () {
    // LED Lights routes
    Route::get('/inventory/led-lights', [InventoryController::class, 'ledLights'])->name('inventory.led-lights');
    Route::get('/inventory/led-lights/create', [InventoryController::class, 'createLedLight'])->name('inventory.led-lights.create');
    Route::post('/inventory/led-lights', [InventoryController::class, 'storeLedLight'])->name('inventory.led-lights.store');
    Route::get('/inventory/led-lights/{inventory}/edit', [InventoryController::class, 'editLedLight'])->name('inventory.led-lights.edit');
    Route::put('/inventory/led-lights/{inventory}', [InventoryController::class, 'updateLedLight'])->name('inventory.led-lights.update');
    Route::delete('/inventory/led-lights/{inventory}', [InventoryController::class, 'destroyLedLight'])->name('inventory.led-lights.destroy');

    // Spices routes
    Route::get('/inventory/spices', [InventoryController::class, 'spices'])->name('inventory.spices');
    Route::get('/inventory/spices/create', [InventoryController::class, 'createSpice'])->name('inventory.spices.create');
    Route::post('/inventory/spices', [InventoryController::class, 'storeSpice'])->name('inventory.spices.store');
    Route::get('/inventory/spices/{inventory}/edit', [InventoryController::class, 'editSpice'])->name('inventory.spices.edit');
    Route::put('/inventory/spices/{inventory}', [InventoryController::class, 'updateSpice'])->name('inventory.spices.update');
    Route::delete('/inventory/spices/{inventory}', [InventoryController::class, 'destroySpice'])->name('inventory.spices.destroy');
});

// User Management routes - Only for admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Salesmen routes
    Route::prefix('salesmen')->group(function () {
        Route::get('', [SalesmanController::class, 'index'])->name('salesmen.index');
        Route::get('/create', [SalesmanController::class, 'create'])->name('salesmen.create');
        Route::post('', [SalesmanController::class, 'store'])->name('salesmen.store');
        Route::get('/{salesman}/edit', [SalesmanController::class, 'edit'])->name('salesmen.edit');
        Route::put('/{salesman}', [SalesmanController::class, 'update'])->name('salesmen.update');
        Route::delete('/{salesman}', [SalesmanController::class, 'destroy'])->name('salesmen.destroy');
    });

    // Warehouse Manager routes
    Route::get('/warehouse-managers', [WarehouseManagerController::class, 'index'])->name('warehouse-managers.index');
    Route::get('/warehouse-managers/create', [WarehouseManagerController::class, 'create'])->name('warehouse-managers.create');
    Route::post('/warehouse-managers', [WarehouseManagerController::class, 'store'])->name('warehouse-managers.store');
    Route::get('/warehouse-managers/{warehouseManager}/edit', [WarehouseManagerController::class, 'edit'])->name('warehouse-managers.edit');
    Route::put('/warehouse-managers/{warehouseManager}', [WarehouseManagerController::class, 'update'])->name('warehouse-managers.update');
    Route::delete('/warehouse-managers/{warehouseManager}', [WarehouseManagerController::class, 'destroy'])->name('warehouse-managers.destroy');
});

// Inventory Request routes
Route::middleware('auth')->group(function () {
    Route::prefix('inventory-requests')->group(function () {
        Route::get('/all', [InventoryRequestController::class, 'allRequests'])->name('inventory-requests.all');
        Route::get('/', [InventoryRequestController::class, 'index'])->name('inventory-requests.index');
        Route::get('/create', [InventoryRequestController::class, 'create'])->name('inventory-requests.create');
        Route::post('/', [InventoryRequestController::class, 'store'])->name('inventory-requests.store');
        Route::get('/{inventoryRequest}', [InventoryRequestController::class, 'show'])->name('inventory-requests.show');
        Route::post('/{inventoryRequest}/approve', [InventoryRequestController::class, 'approve'])->name('inventory-requests.approve');
        Route::post('/{inventoryRequest}/reject', [InventoryRequestController::class, 'reject'])->name('inventory-requests.reject');
        Route::get('/{inventoryRequest}/edit', [InventoryRequestController::class, 'edit'])->name('inventory-requests.edit');
        Route::put('/{inventoryRequest}', [InventoryRequestController::class, 'update'])->name('inventory-requests.update');
        Route::delete('/{inventoryRequest}', [InventoryRequestController::class, 'destroy'])->name('inventory-requests.destroy');
    });

    // Salesmen-only routes for editing/deleting
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
