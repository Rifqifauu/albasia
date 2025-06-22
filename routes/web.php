<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/payrolls/{record}/download-pdf', [PdfController::class, 'payroll'])
    ->name('payroll.download.pdf');

Route::get('/', function () {
    return redirect('/admin');
});
