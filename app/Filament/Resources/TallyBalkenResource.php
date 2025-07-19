<?php

namespace App\Filament\Resources;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TallyBalken;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Resources\TallyBalkenResource\RelationManagers\PalletsRelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\HasManyRepeater;

use Filament\Tables\Columns\TextColumn;

use App\Filament\Resources\TallyBalkenResource\Pages;

class TallyBalkenResource extends Resource
{
    protected static ?string $model = TallyBalken::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Tally Balken';

    public static function form(Form $form): Form
{
    return $form->schema([]);
}


    public static function table(Table $table): Table
    {
        return $table
                ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('no_register')
                    ->label('No Register')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('nomor_polisi')
                    ->label('Nomor Polisi')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Tally')
                    ->dateTime('d M Y, H:i')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                  Tables\Actions\Action::make('download_pdf')
    ->label('Download PDF')
    ->icon('heroicon-o-arrow-down-tray')
    ->color('success')
    ->url(fn ($record) => route('tally.download.pdf', $record)) // 👈 ganti jadi URL
    ->openUrlInNewTab(),
            ])
            ->filters([
              // Filter berdasarkan tanggal
                Filter::make('tanggal_range')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('tanggal_dari')
                                    ->label('Tanggal Dari')
                                    ->placeholder('Pilih tanggal mulai'),
                                DatePicker::make('tanggal_sampai')
                                    ->label('Tanggal Sampai')
                                    ->placeholder('Pilih tanggal akhir'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tally_balken.created_at', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tally_balken.created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['tanggal_dari'] ?? null) {
                            $indicators[] = 'Dari: ' . Carbon::parse($data['tanggal_dari'])->format('d/m/Y');
                        }

                        if ($data['tanggal_sampai'] ?? null) {
                            $indicators[] = 'Sampai: ' . Carbon::parse($data['tanggal_sampai'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                // Filter berdasarkan nomor polisi
                Filter::make('nomor_polisi')
                    ->form([
                        Select::make('nomor_polisi_filter')
                            ->label('Nomor Polisi')
                            ->placeholder('Pilih nomor polisi')
                            ->options(function () {
                                return TallyBalken::select('nomor_polisi')
                                    ->distinct()
                                    ->orderBy('nomor_polisi')
                                    ->pluck('nomor_polisi', 'nomor_polisi')
                                    ->toArray();
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nomor_polisi_filter'],
                            fn (Builder $query, $nopol): Builder => $query->where('tally_balken.nomor_polisi', $nopol),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['nomor_polisi_filter'] ?? null) {
                            return 'Nomor Polisi: ' . $data['nomor_polisi_filter'];
                        }

                        return null;
                    })
        ])
            ->bulkActions([

Tables\Actions\BulkAction::make('download_zip')
    ->label('Download ZIP PDF')
    ->requiresConfirmation()
    ->color('success')
    ->action(function ($records) {
        $zip = new ZipArchive();
        $fileName = 'TallyPDFs_' . now()->format('Ymd_His') . '.zip';
        $filePath = storage_path("app/{$fileName}");

        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($records as $record) {
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
                ])->setPaper([0, 0, 298, 430], 'landscape');

                $safeName = 'Tally-' . preg_replace('/[^a-zA-Z0-9-_]/', '_', $record->no_register) . '.pdf';

                $zip->addFromString($safeName, $pdf->output());
            }

            $zip->close();
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    })
            ]);
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tally';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTallyBalken::route('/'),
            'create' => Pages\CreateTallyBalken::route('/create'),
            'edit' => Pages\EditTallyBalken::route('/{record}/edit'),
            'view' => Pages\ViewTallyBalken::route('/{record}/view'),
        ];
    }
}
