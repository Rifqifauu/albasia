<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KilnDryResource\Pages;
use App\Filament\Resources\KilnDryResource\RelationManagers;
use App\Models\KilnDry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->Time()
                    ->sortable(),
                TextColumn::make('perkiraan_bongkar')
                    ->label('Perkiraan Bongkar')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_bongkar')
                    ->label('Tanggal Bongkar')
                    ->date()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKilnDries::route('/'),
            'create' => Pages\CreateKilnDry::route('/create'),
            'edit' => Pages\EditKilnDry::route('/{record}/edit'),
            'view' => Pages\ViewKilnDry::route('/{record}'),
            'kilndry-scan' => Pages\KilnDryScan::route('scan-barcode/{record}'),
        ];
    }
}
