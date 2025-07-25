<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KubikasiLogResource\Pages;
use App\Filament\Resources\KubikasiLogResource\RelationManagers;
use App\Models\KubikasiLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions;

class KubikasiLogResource extends Resource
{
    protected static ?string $model = KubikasiLog::class;

    protected static ?string $navigationGroup = 'Manajemen Log';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->modifyQueryUsing(fn ($query) =>
                method_exists(KubikasiLog::class, 'queryWithTotalTagihan')
                    ? KubikasiLog::queryWithTotalTagihan()
                    : $query
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Tally')
                    ->date(),
                TextColumn::make('nomor_polisi')
                    ->label('Nomor Polisi'),
                TextColumn::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan tanggal - DIPERBAIKI
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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

                // Filter berdasarkan nomor polisi - DIPERBAIKI
                Filter::make('nomor_polisi')
                    ->form([
                        Select::make('nomor_polisi_filter')
                            ->label('Nomor Polisi')
                            ->placeholder('Pilih nomor polisi')
                            ->options(function () {
                                return KubikasiLog::select('nomor_polisi')
                                    ->distinct()
                                    ->whereNotNull('nomor_polisi')
                                    ->orderBy('nomor_polisi')
                                    ->pluck('nomor_polisi', 'nomor_polisi')
                                    ->toArray();
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nomor_polisi_filter'],
                            fn (Builder $query, $nopol): Builder => $query->where('nomor_polisi', $nopol),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['nomor_polisi_filter'] ?? null) {
                            return 'Nomor Polisi: ' . $data['nomor_polisi_filter'];
                        }
                        return null;
                    }),
            ])
            ->defaultSort('total_tagihan', 'desc')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat Detail')
                    ->url(fn ($record) => route('filament.admin.resources.kubikasi-logs.view', [
                        'tanggal' => $record->created_at->format('Y-m-d'), // Format tanggal
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKubikasiLogs::route('/'),
            'view' => Pages\ViewKubikasiLog::route('/{tanggal}/{nomor_polisi}'),
        ];
    }
}
