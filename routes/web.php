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

Route::get('/materials', [MaterialController::class, 'index'])->name('material.index');
Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');

Route::get('/material/datatable', [MaterialController::class, 'datatable'])->name('material.datatable');


Route::get('/material-stock/material-list', [MaterialStockController::class, 'material_list'])->name('material-stock.material-list');
Route::get('/material-stock/create/{material_id}', [MaterialStockController::class, 'create'])->name('material-stock.create');
Route::post('/material-stock/store', [MaterialStockController::class, 'store'])->name('material-stock.store');