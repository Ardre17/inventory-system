<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryPeriodController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\RawMaterialController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalOrders     = Order::count();
        $lowStock        = Product::whereColumn('stock', '<=', 'stock_min')->count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'stock_min')->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'lowStock',
            'lowStockProducts'
        ));
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('clients', ClientController::class);
    Route::get('/api/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('inventory-periods', InventoryPeriodController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('production-orders', ProductionOrderController::class);
    Route::post('production-orders/{productionOrder}/complete', [ProductionOrderController::class, 'complete'])->name('production-orders.complete');
    Route::resource('raw-materials', RawMaterialController::class);

});

require __DIR__.'/auth.php';