<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;

require __DIR__.'/auth.php';

// === Trang chủ công khai (không cần đăng nhập) ===
Route::get('/', function () {
    return view('home');
});

Route::middleware('auth')->group(function () {

    // === Route profile của Breeze ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === Chức năng dùng chung: User và Admin đều được dùng ===
    Route::get('/dashboard', [DocumentController::class, 'dashboard'])->name('dashboard');
    Route::get('/ocr', [DocumentController::class, 'index']);

    Route::post('/upload', [DocumentController::class, 'upload']);
    Route::post('/upload-back', [DocumentController::class, 'uploadBack'])->name('upload.back');
    Route::post('/upload-word', [DocumentController::class, 'uploadWord'])->name('upload.word');
    Route::post('/upload-excel', [DocumentController::class, 'uploadExcel'])->name('upload.excel');

    Route::post('/preview', [DocumentController::class, 'preview'])->name('preview');

    Route::post('/export-word', [DocumentController::class, 'exportWord']);
    Route::post('/export-selected-word', [DocumentController::class, 'exportSelectedWord'])->name('export.selected.word');
    Route::post('/export-excel', [DocumentController::class, 'exportExcel'])->name('export.excel');

    Route::get('/documents', [DocumentController::class, 'list'])->name('documents.list');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');

    // === Chức năng CHỈ ADMIN ===
    Route::middleware('admin')->group(function () {
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/export-all-excel', [DocumentController::class, 'exportAllExcel'])->name('export.all.excel');

        // Quản lý nhân viên (tạo mới, đổi quyền, xóa tài khoản)
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{id}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});