<?php

use App\Http\Controllers\MaterialController;
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
    return view('welcome');
});

Route::get('/materials', [MaterialController::class, 'index']);

Route::get('/material/create', [MaterialController::class, 'create'])->name('material.create');
Route::post('/material/store', [MaterialController::class, 'store'])->name('material.store');
