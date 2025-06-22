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

                TextInput::make('potongan_kekurangan_jam')
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
            

                // TextColumn::make('jumlah_hari')->label('Hari Kerja'),
                // TextColumn::make('jumlah_lembur')->label('Lembur')->suffix(' Jam'),
                // TextColumn::make('kekurangan_jam')->label('Kurang')->suffix(' Jam'),

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
                Tables\Actions\BulkActionGroup::make([
                ]),
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
