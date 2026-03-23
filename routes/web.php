<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/laporan-pdf', [App\Http\Controllers\PdfController::class, 'laporan'])->name('laporan.pdf');
