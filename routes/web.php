<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', [DocumentController::class, 'dashboard']);
Route::post('/upload', [DocumentController::class, 'upload']);
Route::post('/export-word', [DocumentController::class, 'exportWord']);
Route::get('/ocr', [DocumentController::class, 'index']);

Route::get('/documents', [DocumentController::class, 'list'])
    ->name('documents.list');

Route::get('/documents/{id}', [DocumentController::class, 'show'])
    ->name('documents.show');

Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])
    ->name('documents.destroy');

Route::get('/dashboard', [DocumentController::class, 'dashboard'])
    ->name('dashboard');

Route::post('/upload-word',[DocumentController::class,'uploadWord'])
    ->name('upload.word');

Route::post('/upload-excel',[DocumentController::class, 'uploadExcel'])
    ->name('upload.excel');

Route::post('/preview', [DocumentController::class, 'preview'])
    ->name('preview');
Route::post('/export-selected-word',[DocumentController::class,'exportSelectedWord'])
    ->name('export.selected.word');
Route::post(
    '/export-excel',
    [DocumentController::class,'exportExcel']
);
Route::get(
    '/export-all-excel',
    [DocumentController::class,'exportAllExcel']
)->name('export.all.excel');
