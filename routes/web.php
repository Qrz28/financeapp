<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/qr/login/{user}', [DashboardController::class, 'qrLogin'])
    ->name('qr.login');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/transactions/latest', [TransactionController::class, 'latest'])
        ->name('transactions.latest');

    Route::resource('transactions', TransactionController::class);

});

require __DIR__.'/auth.php';