<?php

namespace App\Filament\Resources\DetailKilnDryRelationManagerResource\RelationManagers;

use App\Models\KubikasiBalken;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'balken'; // Relationship for Balken

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_register')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_balken')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_volume')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('no_register')
            ->columns([
                Tables\Columns\TextColumn::make('no_register')
                    ->label('No Register')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('total_balken')
                    ->label('Total Balken')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_volume')
                    ->label('Total Volume')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->description(function () {
                $rekap = KubikasiBalken::rekapPerGradeInKilnDry($this->ownerRecord->id);

                if ($rekap->isEmpty()) {
                    return 'Belum ada data rekap grade.';
                }

                $result = "Rekap Grade:\n";
                foreach ($rekap as $item) {
                    $result .= "{$item->grade}: {$item->total_jumlah} balken, " . number_format($item->total_volume, 3) . " m³\n";
                }

                return $result;
            })
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('view')
                    ->label('Lihat Detail')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) =>
                        route('filament.admin.resources.tally-balkens.view', ['record' => $record])
                    )
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
