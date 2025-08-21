<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranBalkenResource\Pages;
use App\Filament\Resources\PembayaranBalkenResource\RelationManagers;
use App\Models\PembayaranBalken;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

use Carbon\Carbon;

class PembayaranBalkenResource extends Resource
{
    protected static ?string $model = PembayaranBalken::class;
    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
TextColumn::make('tanggal_tally')
                    ->label('Tanggal Tally')
    ->date('d M y'),
TextColumn::make('no_polisi')
                    ->label('Nomor Polisi'),
TextColumn::make('quantity')
                    ->label('Jumlah Balken'),
TextColumn::make('total_tagihan')
    ->label('Total Tagihan')
    ->money('IDR', true),
TextColumn::make('status')
    ->label('Status Pembayaran')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'pending' => 'warning',
        'selesai' => 'success',
        default => 'gray',
    }),


            ])
          ->filters([
    // Filter Status dengan searchable dropdown
    Tables\Filters\SelectFilter::make('status')
        ->label('Status Pembayaran')
        ->options([
            'pending' => 'Pending',
            'selesai' => 'Selesai',
        ])
        ->searchable(),

    // Filter Tanggal (dari - sampai)
    Tables\Filters\Filter::make('tanggal_tally')
        ->label('Tanggal Tally')
        ->form([
            DatePicker::make('from')->label('Dari Tanggal'),
            DatePicker::make('until')->label('Sampai Tanggal'),
        ])
        ->query(function (Builder $query, array $data) {
            return $query
                ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
        }),
])

            ->actions([
                 Tables\Actions\Action::make('set_selesai')
        ->label('Tandai Selesai')
        ->color('success')
        ->icon('heroicon-o-check-circle')
        ->requiresConfirmation()
        ->visible(condition: fn ($record) => $record->status === 'pending') // tampil hanya kalau status masih pending
        ->action(function ($record) {
            $record->update(['status' => 'selesai']);
        }),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListPembayaranBalkens::route('/'),
        ];
    }
}
