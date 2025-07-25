<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});





require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
