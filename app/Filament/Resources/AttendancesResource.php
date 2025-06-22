<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendancesResource\Pages;
use App\Filament\Resources\AttendancesResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use App\Models\Employee;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendancesResource extends Resource
{
    protected static ?string $model = Attendance::class;
protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Payroll';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Pegawai')
                    ->relationship('employee', 'nama')
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                TimePicker::make('masuk')
                    ->label('Jam Masuk')
                    ->required(),

                TimePicker::make('keluar')
                    ->label('Jam Keluar')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama'),
                TextColumn::make('masuk')
                    ->label('Masuk')
                    ->time('H:i')
                        ->suffix(' WIB'),


                TextColumn::make('keluar')
                    ->label('Keluar')
                    ->time('H:i')
                        ->suffix(' WIB'),


                TextColumn::make('durasi_kerja')->suffix(' Jam'),
                TextColumn::make('kekurangan_jam')->suffix(' Jam'),
                TextColumn::make('lembur')->suffix(' Jam'),
                TextColumn::make('tanggal')->date('d M Y')
,

            ])
            ->filters([
    Filter::make('filter_kombinasi')
        ->label('Filter Pegawai & Tanggal')
        ->form([
            Select::make('employee_id')
                ->label('Nama Pegawai')
                ->relationship('employee', 'nama'),
            DatePicker::make('tanggal')
                ->label('Tanggal'),
        ])
        ->query(function (Builder $query, array $data) {
            return $query
                ->when($data['employee_id'], fn ($q, $id) => $q->where('employee_id', $id))
                ->when($data['tanggal'], fn ($q, $date) => $q->whereDate('tanggal', $date));
        }),
])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendances::route('/create'),
            'edit' => Pages\EditAttendances::route('/{record}/edit'),
        ];
    }
}
