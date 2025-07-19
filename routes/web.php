<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/payrolls/{record}/download-pdf', [PdfController::class, 'payroll'])
    ->name('payroll.download.pdf');
Route::get('/tallies/{record}/download-pdf', [PdfController::class, 'tally'])
    ->name('tally.download.pdf');
Route::post('/kilndry', [\App\Http\Controllers\TallyController::class, 'assign'])->name('scan.qr.assign');


Route::get('/', function () {
    return redirect('/admin');
});
