<?php

namespace App\Filament\Resources\TallyResource\Pages;

use App\Filament\Resources\TallyResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\HasManyRepeater;

class CreateTally extends CreateRecord
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
    ->schema([
        Grid::make([
            'default' => 2,
            'md' => 4,
        ])->schema([
            TextInput::make('nomor_pallet')
                ->label('Nomor Pallet')
                ->hidden()
                ->dehydrated(),

            Select::make('grade')
->options([
    'kotak' => 'KOTAK',
    'ds4' => 'DS 4',
    'ongrade' => 'ON GRADE',
    'allgrade' => 'ALL GRADE',
    'afkir' => 'AFKIR',
])

                ->required()  ->columnSpan([
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
                    if ($state && $lebar && $panjang) {
                        $set('volume', $state * $lebar * $panjang);
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
                    if ($tebal && $state && $panjang) {
                        $set('volume', $tebal * $state * $panjang);
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
                    if ($tebal && $lebar && $state) {
                        $set('volume', $tebal * $lebar * $state);
                    }
                }),

            
            TextInput::make('jumlah')
                ->label('Jumlah')
                ->numeric(),
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
    ->itemLabel(fn (array $state): ?string => $state['nomor_pallet'] ?? 'Pallet')
    ->afterStateUpdated(function (array $state, callable $set) {
    $totalVolume = 0;
    $totalBalken = 0;

    $counter = 1;
    foreach ($state as $key => $item) {
        if (empty($item['nomor_pallet'])) {
            $state[$key]['nomor_pallet'] = (string) $counter;
        }
        $counter++;
    }

    foreach ($state as $item) {
        $volume = isset($item['volume']) && is_numeric($item['volume']) ? floatval($item['volume']) : 0;
        $jumlah = isset($item['jumlah']) && is_numeric($item['jumlah']) ? intval($item['jumlah']) : 0;

        $totalVolume += $volume;
        $totalBalken += $jumlah;
    }

    $set('pallet', $state);
    $set('total_volume', $totalVolume);
    $set('total_balken', $totalBalken);
})


          
        ]);
    }
}
