<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    Route::get('/pdf/challan/{id}', [PdfController::class, 'challan'])->name('pdf.challan');
    Route::get('/pdf/bill/{id}', [PdfController::class, 'bill'])->name('pdf.bill');
    Route::post('/whatsapp/challan/{id}', [PdfController::class, 'sendChallanWhatsApp'])->name('whatsapp.challan');
});
