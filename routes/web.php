<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
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
        Route::resource('notification', NotificationController::class);
        Route::resource('user-data', UserDataController::class);
        Route::delete('user-data.bulk-delete', [UserDataController::class, 'bulkDelete'])->name('user-data.bulk-delete');
    });
    Route::resource('report-clinic', ReportController::class);
    Route::get('report-branch', [ReportController::class, 'branch'])->name('report.branch');
    Route::resource('bug-report', BugReportController::class);
    Route::get('audit', [Controller::class, 'auditable'])->name('audit');
    Route::prefix('report-ho')->group(function () {
        Route::get('/', [ReportController::class, 'headOffice'])->name('report.ho');
        Route::put('/', [ReportController::class, 'approveHeadOffice'])->name('report.approve_ho');
    });
    Route::prefix('export')->group(function () {
        Route::get('clinic', [ReportController::class, 'exportClinic'])->name('export.clinic');
        Route::get('branch', [ReportController::class, 'exportBranch'])->name('export.branch');
        Route::get('ho', [ReportController::class, 'exportHeadOffice'])->name('export.ho');
    });
    Route::prefix('server-side')->group(function () {
        Route::get('kons-klinik', [ReportController::class, 'checkExisting'])
            ->name('report.check-existing');
        Route::get('branch-clinics/{branch}', [ReportController::class, 'getBranchClinics'])
            ->name('server.branch-clinics');
        Route::get('clinic-items/{clinic}', [ReportController::class, 'getClinicItems'])
            ->name('server.clinic-items');
    });
});
Route::resource('login', LoginController::class);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect()->route('login.index');
});
