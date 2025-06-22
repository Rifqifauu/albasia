<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KubikasiBalkenResource\Pages;
use App\Filament\Resources\KubikasiBalkenResource\RelationManagers;
use App\Models\Tallies;
use App\Models\Pallets;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KubikasiBalkenResource extends Resource
{
    protected static ?string $model = Tallies::class;
    protected static ?string $navigationLabel = 'Kubikasi Balken';
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->selectRaw('
                        MIN(id) as id,
                        nomor_polisi, 
                        DATE(created_at) as tanggal_dibuat, 
                        SUM(total_balken) as total_balken, 
                        SUM(total_volume) as total_volume
                    ')
                    ->groupByRaw('nomor_polisi, DATE(created_at)')
                    ->orderByRaw('DATE(created_at) asc, nomor_polisi asc');
            })
            ->columns([
                   TextColumn::make('tanggal_dibuat')
                    ->label('Tanggal Tally')
                    ->date()
                    ,
                TextColumn::make('nomor_polisi')
                    ->label('Nomor Polisi'),
                TextColumn::make('total_balken')
                    ->label('Total Balken')
                    ->numeric(),
            ])                    ->searchable()  

            ->defaultSort(null)
            // Disable actions that don't make sense for grouped data
            ->actions([
                 Tables\Actions\Action::make('view')
        ->label('Lihat Detail')
        ->url(fn ($record) => route('filament.admin.resources.kubikasi-balkens.view', [
            'tanggal' => $record->tanggal_dibuat,
            'nomor_polisi' => $record->nomor_polisi,
        ]))
        ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPluralLabel(): ?string
    {
        return 'Kubikasi Balken';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKubikasiBalkens::route('/'),
        'view' => Pages\ViewKubikasiBalken::route('/{tanggal}/{nomor_polisi}'),
            // Remove create/edit since they don't make sense for aggregated data
            // 'create' => Pages\CreateKubikasiBalken::route('/create'),
            // 'edit' => Pages\EditKubikasiBalken::route('/{record}/edit'),
        ];
    }

    // Override this method to prevent create/edit actions
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}