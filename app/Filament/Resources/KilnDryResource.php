<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KilnDryResource\Pages;
use App\Models\KilnDry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;

class KilnDryResource extends Resource
{
    protected static ?string $model = KilnDry::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('periode_kd')
                        ->label('Periode')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('kode_kd')
                        ->label('Kode')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('tanggal_bakar')
                        ->label('Tanggal Bakar')
                        ->required(),

                    Forms\Components\TimePicker::make('jam_bakar')
                        ->label('Jam Bakar')
                        ->required(),

                    Forms\Components\DatePicker::make('perkiraan_bongkar')
                        ->label('Perkiraan Bongkar')
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_bongkar')
                        ->label('Tanggal Bongkar')
                        ->required(),

                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode_kd')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kode_kd')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_bakar')
                    ->label('Tanggal Bakar')
                    ->date()
                    ->sortable(),
                TextColumn::make('jam_bakar')
                    ->label('Jam Bakar')
                    ->time()
                    ->sortable(),
                TextColumn::make('perkiraan_bongkar')
                    ->label('Perkiraan Bongkar')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_bongkar')
                    ->label('Tanggal Bongkar')
                    ->date()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
            ])
            ->actions([
            Tables\Actions\Action::make('Scan Barcode')
    ->url(fn (KilnDry $record) => KilnDryResource::getUrl('kilndry-scan', [
        'record' => $record->id,
    ]))
    ->icon('heroicon-o-qr-code'),

                Tables\Actions\EditAction::make(),
                TableAction::make('viewDetails')
                    ->label('Lihat')
                    ->url(fn (KilnDry $record): string =>
                        url("/admin/kiln-dries/details-kiln-dry/{$record->id}")
                    )
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relationships if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKilnDries::route('/'),
            'create' => Pages\CreateKilnDry::route('/create'),
            'edit' => Pages\EditKilnDry::route('/{record}/edit'),
            'details-kiln-dry' => Pages\DetailsKilnDry::route('/details-kiln-dry/{kndId}'),
            'kilndry-scan' => Pages\KilnDryScan::route('/scan-barcode/{record}'),
        ];
    }
}
