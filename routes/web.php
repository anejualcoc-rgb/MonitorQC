<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelEditController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataDeleteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ApprovalController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'role:spv'])->group(function () {
   
});
Route::get('/spv/dashboard', [ExcelController::class, 'index_spv'])->name('dashboard_spv');
Route::get('/staff/dashboard', [ExcelController::class, 'index'])->name('dashboard_staff');
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
    Route::get('/notification', [NotificationController::class, 'index'])->name('notification');
    Route::patch('/notification/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notification.read');


    Route::get('/approval/preview/{id}', [ApprovalController::class, 'preview'])->name('approval.preview');
    Route::post('/approval/approve/{id}', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/reject/{id}', [ApprovalController::class, 'reject'])->name('approval.reject');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return view('auth.login');
});
