<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryPeriodController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\ProductRawMaterialController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\SupplyController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
   return redirect()->route('dashboard');
});


Route::middleware(['auth'])->group(function () {


   Route::get('/dashboard', function () {
       $totalProducts    = Product::count();
       $totalCategories  = Category::count();
       $totalOrders      = Order::count();
       $lowStock         = Product::whereColumn('stock', '<=', 'stock_min')->count();
       $lowStockProducts = Product::whereColumn('stock', '<=', 'stock_min')->get();
       return view('dashboard', compact(
           'totalProducts', 'totalCategories', 'totalOrders',
           'lowStock', 'lowStockProducts'
       ));
   })->name('dashboard');


   Route::resource('categories', CategoryController::class);
   Route::resource('suppliers', SupplierController::class);
   Route::resource('clients', ClientController::class);
   Route::get('/api/clients/search', [ClientController::class, 'search'])->name('clients.search');
   Route::resource('products', ProductController::class);
   Route::resource('orders', OrderController::class);
   Route::resource('inventory-periods', InventoryPeriodController::class);
   Route::resource('production-orders', ProductionOrderController::class);
   Route::post('production-orders/{productionOrder}/complete', [ProductionOrderController::class, 'complete'])->name('production-orders.complete');
   Route::resource('raw-materials', RawMaterialController::class);


   Route::post('orders/{order}/dispatch', [OrderController::class, 'dispatch'])->name('orders.dispatch');
   Route::get('orders/barcode', [OrderController::class, 'findByBarcode'])->name('orders.barcode');


   Route::get('supplies', [SupplyController::class, 'index'])->name('supplies.index');
   Route::get('supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');
   Route::post('supplies/{supply}/entry', [SupplyController::class, 'entry'])->name('supplies.entry');
   Route::post('supplies/{supply}/min', [SupplyController::class, 'updateMin'])->name('supplies.updateMin');


   Route::get('product-raw-materials', [App\Http\Controllers\ProductRawMaterialController::class, 'index'])->name('product-raw-materials.index');
   Route::get('product-raw-materials/{product}/edit', [App\Http\Controllers\ProductRawMaterialController::class, 'edit'])->name('product-raw-materials.edit');
   Route::patch('product-raw-materials/{product}', [App\Http\Controllers\ProductRawMaterialController::class, 'update'])->name('product-raw-materials.update');
   Route::patch('orders/{order}/items/{item}', [OrderController::class, 'updateItem'])->name('orders.items.update');
   Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   Route::get('orders/{order}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf');
   Route::get('orders-import', [OrderController::class, 'importForm'])->name('orders.import.form');
   Route::post('orders-import', [OrderController::class, 'import'])->name('orders.import');
   Route::get('orders-csv-template', [OrderController::class, 'csvTemplate'])->name('orders.csv-template');
   Route::post('orders/{order}/update-item', [OrderController::class, 'updateItem'])->name('orders.update-item');
   Route::post('orders/{order}/update-item-price', [OrderController::class, 'updateItemPrice'])->name('orders.update-item-price');


   Route::get('products/{product}/raw-materials', [ProductRawMaterialController::class, 'index'])->name('products.raw-materials');
   Route::post('products/{product}/raw-materials', [ProductRawMaterialController::class, 'store'])->name('products.raw-materials.store');
   Route::delete('products/{product}/raw-materials/{rawMaterial}', [ProductRawMaterialController::class, 'destroy'])->name('products.raw-materials.destroy');
   Route::post('products/{product}/generate-labels', [ProductController::class, 'generateLabels'])->name('products.generate-labels');
});


// Importación masiva de órdenes - PÚBLICA (sin auth)
Route::get('orders/import', [App\Http\Controllers\OrderImportController::class, 'index'])->name('orders.import');
Route::post('orders/import', [App\Http\Controllers\OrderImportController::class, 'import'])->name('orders.import.store');
Route::get('orders/import/template', [App\Http\Controllers\OrderImportController::class, 'downloadTemplate'])->name('orders.import.template');


// Pruebas
Route::get('/prueba-final', function() {
   return 'FUNCIONA PERFECTO';
});


Route::get('/prueba-import', [App\Http\Controllers\OrderImportController::class, 'index']);


// Cargar rutas de auth
require __DIR__.'/auth.php';