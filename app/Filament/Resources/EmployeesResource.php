<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employee;
use Filament\Tables\Filters\SelectFilter; // Tambahkan ini
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Payroll';
protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                 ->label('Nama'),
                TextInput::make('nik')
                 ->label('NIK'),
                TextInput::make('bagian')
                 ->label('Bagian'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                ->label('Nama'),
                TextColumn::make('nik') ->label('NIK'),
                TextColumn::make('bagian') ->label('Bagian'),
            ])
            ->filters([
SelectFilter::make('bagian')
    ->options(
        Employee::query()
            ->distinct('bagian')
            ->pluck('bagian', 'bagian')
            ->toArray()
    )
                
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListEmployees::route('/'),
        ];
    }
}
