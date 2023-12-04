<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('dashboard', [
        'title' => 'Dashboard'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::post('read_notifications',function(){
    //     Auth()->user()->unreadNotifications()->update(['read_at' => now()]);
    //     return back();
    // });
    Route::group(['middleware' => ['role:admin']], function () {

        Route::get('/material', [MaterialController::class, 'index'])->name('material.index');
        Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
        Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');
        Route::get('/material/edit/{id}', [MaterialController::class, 'edit'])->name('material.edit');
        Route::post('/material/update', [MaterialController::class, 'update'])->name('material.update');
        Route::get('/material/destroy/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');
        Route::get('/material/datatable', [MaterialController::class, 'datatable'])->name('material.datatable');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // Route::get('/users/datatables', [UserController::class, 'datatables'])->name('users.datatables');
    });

    Route::group(['middleware' => ['role:admin|purchasing|finance']], function () {
        Route::get('material/export', [MaterialController::class, 'export'])->name('material.export');
    });

    Route::prefix('material-stock')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::post('import', [MaterialStockController::class, 'import'])->name('material-stock.import');
            Route::get('logs', [MaterialStockController::class, 'logs'])->name('material-stock.logs');
            Route::get('logs-serverside', [MaterialStockController::class, 'logs_serverside'])->name('material-stock.logs-serverside');
            Route::get('logs/data', [MaterialStockController::class, 'logsData'])->name('material-stock.logs.data');
            Route::get('export/{created_at?}', [MaterialStockController::class, 'export'])->name('material-stock.export');
        });
        Route::group(['middleware' => ['role:admin|purchasing|finance']], function () {
            Route::get('create/{material_id}', [MaterialStockController::class, 'create'])->name('material-stock.create');
            Route::post('store', [MaterialStockController::class, 'store'])->name('material-stock.store');
            Route::get('edit/{material_id}/{material_stock_id}', [MaterialStockController::class, 'edit'])->name('material-stock.edit');
            Route::post('update', [MaterialStockController::class, 'update'])->name('material-stock.update');
            Route::get('destroy/{id}', [MaterialStockController::class, 'destroy'])->name('material-stock.destroy');
            Route::get('material-list', [MaterialStockController::class, 'material_list'])->name('material-stock.material-list');
            Route::get('stocks/{material_id}', [MaterialStockController::class, 'index'])->name('material-stock.index');
            Route::get('logs', [MaterialStockController::class, 'logs'])->name('material-stock.logs');

            // Semua list stock dengan kode produksi
            Route::get('all-material-list', [MaterialStockController::class, 'all_material_list'])->name('material-stock.all-material-list');
            Route::get('edit-new/{material_stock_id}', [MaterialStockController::class, 'edit_new'])->name('material-stock.edit-new');

            // Route::get('export/{created_at?}', [MaterialStockController::class, 'export'])->name('material-stock.export');
        });
        Route::group(['middleware' => ['role:pic']], function () {
            Route::post('store', [MaterialStockController::class, 'store'])->name('material-stock.store');
            Route::get('edit/{material_id}/{material_stock_id}', [MaterialStockController::class, 'edit'])->name('material-stock.edit');
            Route::post('update', [MaterialStockController::class, 'update'])->name('material-stock.update');
            Route::get('material-list', [MaterialStockController::class, 'material_list'])->name('material-stock.material-list');

            // Semua list stock dengan kode produksi
            Route::get('all-material-list', [MaterialStockController::class, 'all_material_list'])->name('material-stock.all-material-list');
            Route::get('edit-new/{material_stock_id}', [MaterialStockController::class, 'edit_new'])->name('material-stock.edit-new');

            // Route::get('export/{created_at?}', [MaterialStockController::class, 'export'])->name('material-stock.export');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
