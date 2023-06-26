<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialStockController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
        Route::get('/', [MaterialStockController::class, 'material_list'])->name('material-stock.material-list');

        Route::group(['middleware' => ['role:admin']], function () {

            Route::get('/material', [MaterialController::class, 'index'])->name('material.index');

            Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
            Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');

            Route::get('/material/edit/{id}', [MaterialController::class, 'edit'])->name('material.edit');
            Route::post('/material/update', [MaterialController::class, 'update'])->name('material.update');
            Route::get('/material/destroy/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

            Route::get('/material/datatable', [MaterialController::class, 'datatable'])->name('material.datatable');
            Route::get('material/export', [MaterialController::class, 'export'])->name('material.export');
        });

        Route::prefix('material-stock')->group(function() {
            Route::group(['middleware' => ['role:admin']], function () {
                Route::get('create/{material_id}', [MaterialStockController::class, 'create'])->name('material-stock.create');
                Route::post('store', [MaterialStockController::class, 'store'])->name('material-stock.store');
                Route::get('stocks/{material_id}', [MaterialStockController::class, 'index'])->name('material-stock.index');

                Route::get('edit/{material_id}/{material_stock_id}', [MaterialStockController::class, 'edit'])->name('material-stock.edit');
                Route::post('update', [MaterialStockController::class, 'update'])->name('material-stock.update');
                Route::get('destroy/{id}', [MaterialStockController::class, 'destroy'])->name('material-stock.destroy');

                Route::post('import', [MaterialStockController::class, 'import'])->name('material-stock.import');
            });

            Route::get('logs', [MaterialStockController::class, 'logs'])->name('material-stock.logs');


        });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
