<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    Route::get('/pdf/challan/{id}', [PdfController::class, 'challan'])->name('pdf.challan');
    Route::get('/pdf/bill/{id}', [PdfController::class, 'bill'])->name('pdf.bill');
    Route::post('/whatsapp/challan/{id}', [PdfController::class, 'sendChallanWhatsApp'])->name('whatsapp.challan');

    // Reports
    Route::get('/reports/monthly-bills/excel', [ReportController::class, 'monthlyBillsExcel'])->name('reports.monthly-bills.excel');
    Route::get('/reports/monthly-bills/pdf', [ReportController::class, 'monthlyBillsPdf'])->name('reports.monthly-bills.pdf');
});
