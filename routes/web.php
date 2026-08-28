<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [QrCodeController::class, 'index'])->name('dashboard');
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr-codes.store');
    Route::put('/qr-codes/{qrCode}', [QrCodeController::class, 'update'])->name('qr-codes.update');
    Route::delete('/qr-codes/{qrCode}', [QrCodeController::class, 'destroy'])->name('qr-codes.destroy');
    Route::get('/qr-codes/{qrCode}/download', [QrCodeController::class, 'download'])->name('qr-codes.download');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('admin.index');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});