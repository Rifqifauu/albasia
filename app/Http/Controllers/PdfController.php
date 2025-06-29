<?php

namespace App\Http\Controllers;

use App\Models\Payrolls;
use App\Models\Tallies;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PdfController extends Controller
{
    public function payroll(Payrolls $record)
    {
        $pdf = Pdf::loadView('pdf.payroll', [
            'record' => $record,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Slip-Gaji-' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $record->employee->nama) . '.pdf');
    }

    public function tally(Tallies $record)
    {
        $qrCode = (new QRCode(
            new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'imageBase64' => true,
                'scale' => 8,
            ])
        ))->render($record->no_register);

        $pdf = Pdf::loadView('pdf.tally', [
            'tally' => $record,
            'qrCode' => $qrCode,
        ])->setPaper([0, 0, 298, 430], 'landscape'); // slip custom mm

        $filename = 'Tally-' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $record->no_register) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->stream()),
            $filename
        );
    }
}
