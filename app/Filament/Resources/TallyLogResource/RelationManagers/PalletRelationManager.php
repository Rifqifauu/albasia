<?php

namespace App\Filament\Resources\TallyLogResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PalletRelationManager extends RelationManager
{
    protected static string $relationship = 'pallet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tally_id')
                    ->required()
                    ->maxLength(255),
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
