<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollsResource\Pages;
use App\Models\Payrolls;
use Barryvdh\DomPDF\Facade\Pdf; // untuk PDF
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use \ZipArchive;

class PayrollsResource extends Resource
{
    protected static ?string $model = Payrolls::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payroll';
    protected static ?string $pluralLabel = 'Payrolls';
protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Payroll';
    protected static ?string $label = 'Payroll';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Pegawai')
                    ->relationship('employee', 'nama')
                    ->required(),

                DatePicker::make('periode_awal')
                    ->label('Periode Awal')
                    ->required(),

                DatePicker::make('periode_akhir')
                    ->label('Periode Akhir')
                    ->required(),

                TextInput::make('upah')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('tunjangan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('premi_kehadiran')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                      TextInput::make('potongan_bpjs')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('rate_potongan_jam')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama')->label('Pegawai')->searchable(),

                TextColumn::make('periode_awal')
                    ->label('Periode Awal')
                    ->date('d M Y'),

                TextColumn::make('periode_akhir')
                    ->label('Periode Akhir')
                    ->date('d M Y'),
            

                TextColumn::make('jumlah_hari')->label('Hari Kerja'),
                TextColumn::make('jumlah_lembur')->label('Lembur')->suffix(' Jam'),
                TextColumn::make('kekurangan_jam')->label('Kurang')->suffix(' Jam'),

                // TextColumn::make('total_upah')->money('IDR', true),
                // TextColumn::make('total_lembur')->money('IDR', true),
                // TextColumn::make('total_tunjangan')->money('IDR', true),
                // TextColumn::make('total_premi_kehadiran')->money('IDR', true),

                // TextColumn::make('potongan_bpjs')->label('BPJS')->money('IDR', true),
                // TextColumn::make('potongan_kekurangan_jam')->label('Pot. Jam')->money('IDR', true),

                TextColumn::make('penerimaan')->label('Total Penerimaan')->money('IDR', true)->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
           Tables\Actions\Action::make('download_pdf')
    ->label('Download PDF')
    ->icon('heroicon-o-arrow-down-tray')
    ->color('success')
    ->url(fn ($record) => route('payroll.download.pdf', $record)) // 👈 ganti jadi URL
    ->openUrlInNewTab(),

            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('download_zip')
                    ->label('Download ZIP PDF')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                         $zip = new ZipArchive();
        $fileName = 'PayrollPDF_' . now()->format('Ymd_His') . '.zip';
        $filePath = storage_path("app/{$fileName}");
                        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($records as $record) {
               

                 $pdf = Pdf::loadView('pdf.payroll', [
            'record' => $record,
        ])->setPaper('a4', 'landscape');


                $safeName = 'Payroll-' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $record->employee->nama) . '.pdf';

                $zip->addFromString($safeName, $pdf->output());
            }

            $zip->close();
        } return response()->download($filePath)->deleteFileAfterSend(true);
    })
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayrolls::route('/create'),
            'edit' => Pages\EditPayrolls::route('/{record}/edit'),
            'view' => Pages\ViewPayroll::route('/{record}/view'),
        ];
    }
}
