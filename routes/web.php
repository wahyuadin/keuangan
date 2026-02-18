<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RkapController;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('master')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('item', ItemController::class);
        Route::resource('branch-office', BranchController::class);
        Route::resource('clinic', ClinicController::class);
        Route::resource('rkap', RkapController::class);
        Route::resource('sla', SlaController::class);
        Route::resource('user-data', UserDataController::class);
    });
    Route::resource('report-clinic', ReportController::class);
    Route::get('report-branch', [ReportController::class, 'branch'])->name('report.branch');
    Route::prefix('report-ho')->group(function () {
        Route::get('/', [ReportController::class, 'headOffice'])->name('report.ho');
        Route::put('/', [ReportController::class, 'approveHeadOffice'])->name('report.approve_ho');
    });
    Route::get('audit', [Controller::class, 'auditable'])->name('audit');
});
Route::resource('login', LoginController::class);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect()->route('login.index');
});
