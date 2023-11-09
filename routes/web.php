<?php

use App\Http\Controllers\RandomNumberGeneration;
use App\Http\Controllers\TransactionController;
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
    return view('rnum');
});

Route::post('storeTransaction', [TransactionController::class, 'storeTransaction'])->name('storeTransaction');
Route::get('generateRandomNum/{length}', [RandomNumberGeneration::class, 'getRandomNum'])->name('generateRandomNum/{length}');