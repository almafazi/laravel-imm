<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialStockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/', [MaterialController::class, 'index'])->name('material.index');

Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');

Route::get('/material/edit/{id}', [MaterialController::class, 'edit'])->name('material.edit');
Route::post('/material/update', [MaterialController::class, 'update'])->name('material.update');
Route::get('/material/destroy/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

Route::get('/material/datatable', [MaterialController::class, 'datatable'])->name('material.datatable');

Route::prefix('material-stock')->group(function() {
    Route::get('material-list', [MaterialStockController::class, 'material_list'])->name('material-stock.material-list');
    Route::get('create/{material_id}', [MaterialStockController::class, 'create'])->name('material-stock.create');
    Route::post('store', [MaterialStockController::class, 'store'])->name('material-stock.store');
    Route::get('stocks/{material_id}', [MaterialStockController::class, 'index'])->name('material-stock.index');

    Route::get('edit/{material_id}/{material_stock_id}', [MaterialStockController::class, 'edit'])->name('material-stock.edit');
    Route::post('update', [MaterialStockController::class, 'update'])->name('material-stock.update');
    Route::get('destroy/{id}', [MaterialStockController::class, 'destroy'])->name('material-stock.destroy');

    Route::get('logs', [MaterialStockController::class, 'logs'])->name('material-stock.logs');

});
