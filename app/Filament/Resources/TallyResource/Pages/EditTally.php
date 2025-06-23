<?php

namespace App\Filament\Resources\TallyResource\Pages;

use App\Filament\Resources\TallyResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\HasManyRepeater;

class EditTally extends EditRecord
{
    protected static string $resource = TallyResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make([
                'default' => 2,
                'md' => 4,
            ])->schema([
                TextInput::make('no_register')
                    ->label('No Register')
                    ->required(),

                TextInput::make('tally_man')
                    ->label('Tally Man')
                    ->required(),

                TextInput::make('nomor_polisi')
                    ->label('Nomor Polisi')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'basah' => 'Basah',
                        'kering' => 'Kering',
                    ])
                    ->required(),

                TextInput::make('total_volume')
                    ->label('Total Volume')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('total_balken')
                    ->label('Total Balken')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
            ]),

            HasManyRepeater::make('pallet')
                ->relationship('pallet')
                ->label('Data Pallet')
                ->dehydrated()
                ->schema([
                    Grid::make([
                        'default' => 2,
                        'md' => 4,
                    ])->schema([
                        

                        Select::make('grade')
                            ->options([
                                'kotak' => 'KOTAK',
                                'ds4' => 'DS 4',
                                'ongrade' => 'ON GRADE',
                                'allgrade' => 'ALL GRADE',
                                'afkir' => 'AFKIR',
                            ])
                            ->required()
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                            ]),

                        TextInput::make('tebal')
                            ->label('Tebal')
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $lebar = $get('lebar');
                                $panjang = $get('panjang');
                                $jumlah = $get('jumlah');
                                if ($state && $lebar && $panjang && $jumlah) {
                                    $set('volume', $state * $lebar * $panjang * $jumlah);
                                }
                            }),

                        TextInput::make('lebar')
                            ->label('Lebar')
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $tebal = $get('tebal');
                                $panjang = $get('panjang');
                                $jumlah = $get('jumlah');
                                if ($tebal && $panjang && $state && $jumlah) {
                                    $set('volume', $tebal * $panjang * $state * $jumlah);
                                }
                            }),

                        TextInput::make('panjang')
                            ->label('Panjang')
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $tebal = $get('tebal');
                                $lebar = $get('lebar');
                                $jumlah = $get('jumlah');
                                if ($tebal && $lebar && $state && $jumlah) {
                                    $set('volume', $tebal * $lebar * $state * $jumlah);
                                }
                            }),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $tebal = $get('tebal');
                                $lebar = $get('lebar');
                                $panjang = $get('panjang');
                                if ($tebal && $lebar && $panjang && $state) {
                                    $set('volume', $tebal * $lebar * $panjang * $state);
                                }
                            }),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                            ]),
                    ]),
                ])
                ->defaultItems(1)
                ->minItems(1)
                ->collapsible()
                ->collapsed()
                ->columnSpanFull()
                ->itemLabel( 'Pallet'),
        ]);
    }
protected function fillForm(): void
{
    parent::fillForm();

    // Hapus value `volume` agar tidak tampil dari database
    $state = $this->form->getState();

    if (isset($state['pallet']) && is_array($state['pallet'])) {
        foreach ($state['pallet'] as $i => &$item) {
            $item['volume'] = null;
        }
        $this->form->fill(array_merge($state, [
            'pallet' => $state['pallet'],
        ]));
    }
}

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalVolume = 0;
        $totalBalken = 0;

        if (!isset($data['pallet']) || !is_array($data['pallet'])) {
            $data['total_volume'] = 0;
            $data['total_balken'] = 0;
            return $data;
        }

        foreach ($data['pallet'] as $index => &$pallet) {
            unset($pallet['id'], $pallet['created_at'], $pallet['updated_at'], $pallet['tally_id']);

            $tebal = floatval($pallet['tebal'] ?? 0);
            $lebar = floatval($pallet['lebar'] ?? 0);
            $panjang = floatval($pallet['panjang'] ?? 0);
            $jumlah = intval($pallet['jumlah'] ?? 0);

            $volume = $tebal * $lebar * $panjang * $jumlah;
            $pallet['volume'] = $volume;

            $totalVolume += $volume;
            $totalBalken += $jumlah;
        }

        $data['total_volume'] = $totalVolume;
        $data['total_balken'] = $totalBalken;

        return $data;
    }
    protected function afterSave(): void
{
    // Redirect ke halaman edit ini sendiri untuk merefresh form dan menampilkan data terbaru
$this->redirect(static::getUrl(['record' => $this->record->getKey()]));
}

}
