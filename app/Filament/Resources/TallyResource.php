<?php

namespace App\Filament\Resources;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

use App\Models\Tallies;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Resources\TallyResource\RelationManagers\PalletsRelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\HasManyRepeater;

use Filament\Tables\Columns\TextColumn;

use App\Filament\Resources\TallyResource\Pages;

class TallyResource extends Resource
{
    protected static ?string $model = Tallies::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Daftar Tally';

    public static function form(Form $form): Form
{
    return $form->schema([]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_register')
                    ->label('No Register'),
                TextColumn::make('nomor_polisi')
                    ->label('Nomor Polisi'),
                TextColumn::make('total_balken')
                    ->label('Total Balken'),
                TextColumn::make('total_volume')
                    ->label('Total Volume'),
                TextColumn::make('created_at')
                    ->label('Tanggal Tally')
                    ->dateTime('d M Y, H:i')
                    ->wrap(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                  Action::make('unduh_pdf')
        ->label('Unduh PDF')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->action(function ($record) {
            $qrCode = (new QRCode(
                new QROptions([
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'imageBase64' => true,
                    'scale' => 8,
                ])
            ))->render($record->no_register);

            $pdf = Pdf::loadView('pdf.tally', [
                'tally' => $record,
                'qrCode' => $qrCode,
            ])->setPaper([0, 0, 298, 420], 'landscape') ;

            return response()->streamDownload(
                fn () => print($pdf->stream()),
                'tally-' . $record->id . '.pdf'
            );
        }),
            ])
            ->filters([
            Filter::make('created_at')
                ->form([
                    DatePicker::make('tanggal'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['tanggal'], fn ($query, $date) => 
                            $query->whereDate('created_at', $date));
                }),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
    
    public static function getPluralLabel(): ?string
    {
        return 'Tally';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTallies::route('/'),
            'create' => Pages\CreateTally::route('/create'),
            'edit' => Pages\EditTally::route('/{record}/edit'),
            'view' => Pages\ViewTally::route('/{record}/view'),
        ];
    }
}
