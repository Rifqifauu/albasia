<?php

namespace App\Filament\Resources\TallyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;


class PalletsRelationManager extends RelationManager
{
    protected static string $relationship = 'pallet';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // Tambahkan field sesuai kebutuhan
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('no')
                ->label('No')
                ->state(fn ($record, $livewire) => $livewire->getTableRecords()->search(fn ($r) => $r->getKey() === $record->getKey()) + 1),
            TextColumn::make('grade'),
            TextColumn::make('tebal'),
            TextColumn::make('lebar'),
            TextColumn::make('panjang'),
            TextColumn::make('volume')
    ->formatStateUsing(fn ($state) => number_format($state / 1000000, 3) . ' m³'),
            TextColumn::make(name: 'jumlah'),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }


public function getTableQuery(): Builder
{
    return $this->getRelationship()
        ->getQuery()
->orderBy('grade', 'asc')
        ->orderByDesc('tebal')
        ->orderByDesc('lebar')
        ->orderByDesc('panjang');
}

}
