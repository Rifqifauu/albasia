<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Tally</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .section { margin-bottom: 30px; }
        .label { font-weight: bold; display: inline-block; width: 150px; }
        .qr-code { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
   <div style="display: table; width: 100%;">
    <div style="display: table-cell; width: 50%; vertical-align: top;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>QR Code</h2>
            <img src="{{ $qrCode }}" alt="QR Code">
        </div>
    </div>

    <div style="display: table-cell; width: 50%; vertical-align: top;">
        <h2>Detail Informasi</h2>
        <h2>&nbsp;</h2>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">No Register:</span> {{ $tally->no_register }}</p>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">Tally Man:</span> {{ $tally->tally_man }}</p>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">Nomor Polisi:</span> {{ $tally->nomor_polisi }}</p>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">Total Balken:</span> {{ $tally->total_balken }}</p>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">Total Volume:</span>   {{ number_format($tally->total_volume / 1000000, 3) }} m³
</p>
        <p><span style="font-weight: bold; display: inline-block; width: 150px;">Tanggal Dibuat:</span> {{ $tally->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

</body>
</html>
