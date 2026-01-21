<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RkapController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('master')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('item', ItemController::class);
        Route::resource('branch-office', BranchController::class);
        Route::resource('rkap', RkapController::class);
        Route::resource('user-data', UserDataController::class);
    });
    Route::resource('/', ReportController::class);
    Route::get('audit', [Controller::class, 'auditable'])->name('audit');
});
Route::resource('login', LoginController::class);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
