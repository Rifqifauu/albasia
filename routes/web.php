<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/payrolls/{record}/download-pdf', [PdfController::class, 'payroll'])
    ->name('payroll.download.pdf');
Route::get('/tallybalken/{record}/download-pdf', [PdfController::class, 'tallybalken'])
    ->name('tallybalken.download.pdf');
Route::get('/tallylog/{record}/download-pdf', [PdfController::class, 'tallylog'])
    ->name('tallylog.download.pdf');
Route::post('/kilndry', [\App\Http\Controllers\TallyController::class, 'assign'])->name('scan.qr.assign');


Route::get('/', function () {
    return redirect('/admin');
});
