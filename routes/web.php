<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/payrolls/{record}/download-pdf', [PdfController::class, 'payroll'])
    ->name('payroll.download.pdf');
Route::get('/tallies/{record}/download-pdf', [PdfController::class, 'tally'])
    ->name('tally.download.pdf');


Route::get('/', function () {
    return redirect('/admin');
});
