<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelEditController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataDeleteController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\DefectInfoController;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
use App\Http\Controllers\ForgotPasswordController;

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});


// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'role:spv'])->group(function () {
   
});
Route::get('/spv/dashboard', [ExcelController::class, 'index_spv'])->name('dashboard_spv');
Route::get('/spv/produksi', [ProduksiController::class, 'index'])->name('produksi.index_spv');
Route::get('/spv/defect', [DefectInfoController::class, 'index'])->name('defect.index_spv');
Route::get('/staff/dashboard', [ExcelController::class, 'index'])->name('dashboard_staff');
Route::get('/manager/dashboard', [ExcelController::class, 'index_manager'])->name('dashboard_manager');
Route::middleware(['auth', 'role:staff'])->group(function () {
    
});


Route::middleware('auth')->group(function () {
    Route::get('/upload', [ExcelController::class, 'uploadPage'])->name('upload.page');
    Route::post('/import', [DataImportController::class, 'import'])->name('import');
    Route::get('/dashboard/production/create', [ExcelEditController::class, 'create'])->name('data.create');
    Route::post('/dashboard/production/store', [ExcelEditController::class, 'store'])->name('data.store');
    Route::get('/dashboard/input', [DefectController::class, 'data'])->name('datainput');
    Route::get('/dashboard/defect/create', [DefectController::class, 'create'])->name('defect.create');
    Route::post('/dashboard/defect/store', [DefectController::class, 'store'])->name('defect.store');
    Route::get('/export', [ExcelExportController::class, 'export'])->name('export');
    Route::post('/delete-all', [DataDeleteController::class, 'deleteAll'])->name('delete.all');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
    Route::patch('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');

    Route::get('/produksi/detail/{id}', [ProduksiController::class, 'show'])->name('produksi.show');

    Route::get('/approval/list', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/approval/preview/{id}', [ApprovalController::class, 'preview'])->name('approval.preview');
    Route::post('/approval/approve/{id}', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/reject/{id}', [ApprovalController::class, 'reject'])->name('approval.reject');

    Route::get('/analisis/periode', [AnalyticsController::class, 'periode'])->name('periode');
    Route::get('/analisis/line', [AnalyticsController::class, 'line'])->name('line');


    Route::get('/report', [ExcelExportController::class, 'index'])->name('report_manager');
    Route::get('/report/spv', [ExcelExportController::class, 'index_spv'])->name('report_spv');
    Route::get('/report/process', [ExcelExportController::class, 'export_manager'])->name('export.manager');
    Route::get('/report/process/spv', [ExcelExportController::class, 'export_spv'])->name('export.spv');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

