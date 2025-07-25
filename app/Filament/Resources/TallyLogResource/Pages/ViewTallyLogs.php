<?php

namespace App\Filament\Resources\TallyLogResource\Pages;

use App\Filament\Resources\TallyLogResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf; // untuk PDF

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\TallyLogResource\RelationManagers\PalletRelationManager;

class ViewTallyLogs extends ViewRecord
{
    protected static string $resource = TallyLogResource::class;

    public function getRelationManagers(): array
    {
        return [
            PalletRelationManager::class,
        ];
    }

   public function getHeaderActions(): array
{
    return [
        Action::make('edit')
            ->label('Edit')
            ->url(fn () => static::getResource()::getUrl('edit', ['record' => $this->record]))
            ->icon('heroicon-o-pencil-square'),

        Action::make('download_pdf')
            ->label('Download PDF')
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
                ])->setPaper([0, 0, 298, 420], 'landscape'); // A6 dalam pt

                return response()->streamDownload(
                    fn () => print($pdf->stream()),
                    'tally-' . $record->id . '.pdf'
                );
            }),
    ];
}



    public function infolist(Infolist $infolist): Infolist
    {
        // Generate QR Code dengan ukuran yang lebih besar
        $qr = (new QRCode(
            new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'imageBase64' => true,
                'scale' => 8, // Perbesar skala QR code
                'imageTransparent' => false,
            ])
        ))->render($this->record->no_register);

        return $infolist->schema([
            // Section untuk QR Code
            Section::make('QR Code')
                ->schema([
                    ImageEntry::make('qr_code')
                        ->label('')
                        ->getStateUsing(fn () => $qr)
                        ->size(200) // Ukuran QR code yang lebih besar
                        ->alignment('center'),
                ])
                ->columnSpan(['default' => 2, 'lg' => 1])
                ->extraAttributes(['class' => 'text-center']),

            // Section untuk informasi detail
            Section::make('Detail Informasi')
                ->schema([
                    Grid::make(['default' => 3])
                        ->schema([
                            TextEntry::make('no_register')
                                ->label('No Register')
                                ->weight('bold')
                                ->size('sm')
                                ->columnspan(2),
                            TextEntry::make('tally_man')
                                ->label('Tally Man')
                                ->weight('bold')
                                ->size('sm'),

                            TextEntry::make('nomor_polisi')
                                ->label('Nomor Polisi')
                                ->weight('bold')
                                ->size('sm'),

                            TextEntry::make('total_log')
                                ->label('Total Log')
                                ->badge()
                                ->color('primary'),

                          TextEntry::make('total_volume')
    ->label('Total Volume')
    ->badge()
    ->color('success')
    ->formatStateUsing(fn ($state) => number_format($state / 1000000, 3) . ' m³'),
                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('created_at')
                                ->label('Tanggal Dibuat')
                                ->dateTime('d M Y, H:i')
                                ->weight('medium')
                                ->columnspan(2),
                        ]),
                ])
                ->columnSpan(['default' => 2, 'lg' => 1]),
        ])
        ->columns(['default' => 1, 'lg' => 2]);
    }
}
