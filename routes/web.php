<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::get('/transactions', [TransactionController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
Route::get('/transactions/data', [TransactionController::class, 'getTransactionsData'])->name('transactions.data');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');




require __DIR__ . '/auth.php';