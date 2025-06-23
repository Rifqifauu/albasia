<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 11px;
            box-sizing: border-box;
        }
        body {
             border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            color: #000;
        }
        h3 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 4px 0;
            vertical-align: top;
        }
        td.label {
            color: #555;
            width: 50%;
        }
        .total {
            font-weight: bold;
            color: #000;
        }
        .box {
             border: 1px solid #ccc;
            border-radius: 4px;           
            padding: 10px 15px;
        }
        .layout-table {
            width: 100%;
            table-layout: fixed;
        }
        .layout-table td {
            vertical-align: top;
            padding: 5px;
        }
    </style>
</head>
<body>

    <!-- Baris 1: Informasi Pegawai dan Periode -->
    <table class="layout-table">
        <tr>
            <td width="50%">
                <div class="box">
                    <h3>Informasi Pegawai</h3>
                    <table>
                        <tr><td class="label">Nama</td><td>{{ $record->employee->nama }}</td></tr>
                        <tr><td class="label">Bagian</td><td>{{ $record->employee->bagian }}</td></tr>
                    </table>
                    <hr style="border-top: 1px solid #ccc; margin: 6px 0;">
                    <table>
                        <tr><td class="label"><strong>Total Penerimaan</strong></td><td class="total">{{ 'Rp ' . number_format($record->penerimaan, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </td>
            <td width="50%">
                <div class="box">
                    <h3>Periode & Kehadiran</h3>
                    <table>
                        <tr><td class="label">Mulai Dari</td><td>{{ $record->periode_awal }}</td></tr>
                        <tr><td class="label">Sampai Dengan</td><td>{{ $record->periode_akhir }}</td></tr>
                        <tr><td class="label">Jumlah Hari</td><td>{{ $record->jumlah_hari }}</td></tr>
                        <tr><td class="label">Lembur</td><td>{{ $record->jumlah_lembur }} Jam</td></tr>
                       <tr> <td class="label">Kurang</td><td>{{ $record->kekurangan_jam }} Jam</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Baris 2: Komponen Upah, Potongan, Total -->
    <table class="layout-table">
        <tr>
            <td width="33%">
                <div class="box">
                    <h3>Komponen Upah</h3>
                    <table>
                        <tr><td class="label">Upah Pokok</td><td>{{ 'Rp ' . number_format($record->upah, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Tunjangan</td><td>{{ 'Rp ' . number_format($record->tunjangan, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Premi</td><td>{{ 'Rp ' . number_format($record->premi_kehadiran, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </td>
            <td width="33%">
                <div class="box">
                    <h3>Potongan</h3>
                    <table>
                        <tr><td class="label">BPJS</td><td>{{ 'Rp ' . number_format($record->potongan_bpjs, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Jam Kurang</td><td>{{ 'Rp ' . number_format($record->potongan_kekurangan_jam, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </td>
            <td width="33%">
                <div class="box">
                    <h3>Total Komponen</h3>
                    <table>
                        <tr><td class="label">Total Upah</td><td>{{ 'Rp ' . number_format($record->total_upah, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Total Lembur</td><td>{{ 'Rp ' . number_format($record->total_lembur, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Total Tunjangan</td><td>{{ 'Rp ' . number_format($record->total_tunjangan, 0, ',', '.') }}</td></tr>
                        <tr><td class="label">Total Premi</td><td>{{ 'Rp ' . number_format($record->total_premi_kehadiran, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
