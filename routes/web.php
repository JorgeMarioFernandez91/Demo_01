<?php

use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tickets');

Route::middleware('guest')->group(function () {
    Route::get('/login', [DemoLoginController::class, 'create'])->name('login');
    Route::post('/login/{tenant}', [DemoLoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [DemoLoginController::class, 'destroy'])->name('logout');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});
