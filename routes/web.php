<?php

use App\Http\Controllers\CustomerStatementController;
use App\Livewire\InvoicePDF;
use App\Livewire\SaleInvoice;

Route::middleware(['auth', 'lang'])->group(function () {
    Route::get('/print-invoice/{purchase}', InvoicePDF::class)->name('print-invoice');
    Route::get('/print-sale-invoice/{sale}', SaleInvoice::class)->name('print-sale-invoice');
    Route::get('/customer-statement-print/{customer}', [CustomerStatementController::class, 'print'])->name('customer.statement.print');

    Route::get('/customer-statement-download/{customer}/{filename}', [CustomerStatementController::class, 'download'])->name('customer.statement.download');
});
