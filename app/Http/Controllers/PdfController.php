<?php

namespace App\Http\Controllers;

use App\Models\Payrolls;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function payroll(Payrolls $record)
    {
        $pdf = Pdf::loadView('pdf.payroll', [
            'record' => $record,
        ])->setPaper('a4', 'potrait');

        return $pdf->download('Slip-Gaji-' . $record->employee->nama . '.pdf');
    }
}

