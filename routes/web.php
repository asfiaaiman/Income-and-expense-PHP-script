<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function() {
    // categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::inertia('categories/create', 'categories/Create')->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');

    // transactions
    Route::resource('transactions', TransactionController::class);
});





require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
