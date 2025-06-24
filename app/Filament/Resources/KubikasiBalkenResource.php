<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KubikasiBalkenResource\Pages;
use App\Filament\Resources\KubikasiBalkenResource\RelationManagers;
use App\Models\Tallies;
use App\Models\Pallets;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class KubikasiBalkenResource extends Resource
{
    protected static ?string $model = Tallies::class;
    protected static ?int $navigationSort = 4;
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
        ->leftJoin('pallets', 'tallies.id', '=', 'pallets.tally_id')
        ->leftJoinSub(
            \DB::table('costs')
                ->selectRaw('grade, harga')
                ->where('tipe', 'balken'),
            'filtered_costs',
            function ($join) {
                $join->on(\DB::raw('UPPER(pallets.grade)'), '=', 'filtered_costs.grade');
            }
        )
        ->selectRaw('
            MIN(tallies.id) as id,
            tallies.nomor_polisi, 
            DATE(tallies.created_at) as created_at, 
            COALESCE(SUM(pallets.volume * filtered_costs.harga), 0) as total_tagihan
        ')
        ->groupByRaw('tallies.nomor_polisi, DATE(tallies.created_at)')
        ->orderByRaw('DATE(tallies.created_at) asc, tallies.nomor_polisi asc');
})

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
                                fn (Builder $query, $date): Builder => $query->whereDate('tallies.created_at', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tallies.created_at', '<=', $date),
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
                                return Tallies::select('nomor_polisi')
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
                            fn (Builder $query, $nopol): Builder => $query->where('tallies.nomor_polisi', $nopol),
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
                    ->url(fn ($record) => route('filament.admin.resources.kubikasi-balkens.view', [
                        'tanggal' => $record->created_at,
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
        ];
    }

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