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
                        ->unique(ignoreRecord: true)
                         ->validationMessages([
        'unique' => 'Kode Register sudah pernah digunakan.',
    ])
                    ,

                TextInput::make('tally_man')
                    ->label('Tally Man')
                    ,

                TextInput::make('nomor_polisi')
                    ->label('Nomor Polisi')
                    ,

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'basah' => 'Basah',
                        'kering' => 'Kering',
                    ])
                   
                    ->placeholder('Pilih Status')
                    ,

                TextInput::make('total_volume')
                    ->label('Total Volume')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0), // Add default value

                TextInput::make('total_balken')
                    ->label('Total Balken')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->default(0), // Add default value
            ]),

     Grid::make([
                'default' => 2,
                'md' => 4,
            ])->schema([
                 Select::make('grade_default')
    ->label('Grade Default')
    ->options([
        'kotak' => 'KOTAK',
        'ds4' => 'DS 4',
        'ongrade' => 'ON GRADE',
        'allgrade' => 'ALL GRADE',
        'afkir' => 'AFKIR',
    ])
    ->reactive(),
TextInput::make('tebal_default')
    ->label('Tebal Default')
    ->numeric()
    ->reactive(),
TextInput::make('panjang_default')
    ->label('Panjang Default')
    ->numeric()
    ->reactive(),
    TextInput::make('jumlah_pallet')
    ->label('Jumlah Pallet')
    ->numeric()
    ->default(1)
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $jumlah = intval($state);

        $grade = $get('grade_default');
        $tebal = $get('tebal_default');
        $panjang = $get('panjang_default');

        if ($jumlah > 0 && $jumlah <= 100 && $grade && $tebal) {
            $pallets = collect(range(1, $jumlah))->map(fn () => [
                'grade' => $grade,
                'tebal' => $tebal,
                'lebar' => null,
                'panjang' => $panjang,
                'jumlah' => null,
                'volume' => 0,
            ])->toArray();

            $set('pallet', $pallets);
        }
    }),   
]),         HasManyRepeater::make('pallet')
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
                            ->placeholder('Pilih Grade')
                            
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
                                $this->calculateVolume($state, $get, $set);
                                $this->updateTotals($get, $set);
                            }),

                        TextInput::make('lebar')
                            ->label('Lebar')
                            ->numeric()
                           
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $this->calculateVolume($state, $get, $set);
                                $this->updateTotals($get, $set);
                            }),

                        TextInput::make('panjang')
                            ->label('Panjang')
                            ->numeric()
                            
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $this->calculateVolume($state, $get, $set);
                                $this->updateTotals($get, $set);
                            }),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                           
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $this->calculateVolume($state, $get, $set);
                                $this->updateTotals($get, $set);
                            }),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0) // Add default value
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
                ->itemLabel('Pallet')
                ,
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

    // Helper method to calculate volume for individual pallet
    private function calculateVolume($state, callable $get, callable $set): void
    {
        $tebal = floatval($get('tebal') ?? 0);
        $lebar = floatval($get('lebar') ?? 0);
        $panjang = floatval($get('panjang') ?? 0);
        $jumlah = intval($get('jumlah') ?? 0);
        
        if ($tebal && $lebar && $panjang && $jumlah) {
            $volume = $tebal * $lebar * $panjang * $jumlah;
            $set('volume', $volume);
        }
    }

    // Helper method to update totals
    private function updateTotals(callable $get, callable $set): void
    {
        $palletData = $get('../../pallet') ?? [];
        $totalVolume = 0;
        $totalBalken = 0;

        foreach ($palletData as $pallet) {
            $volume = floatval($pallet['volume'] ?? 0);
            $jumlah = intval($pallet['jumlah'] ?? 0);
            
            $totalVolume += $volume;
            $totalBalken += $jumlah;
        }

        $set('../../total_volume', $totalVolume);
        $set('../../total_balken', $totalBalken);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalVolume = 0;
        $totalBalken = 0;

        // Ensure we have default values
        if (!isset($data['total_volume'])) {
            $data['total_volume'] = 0;
        }
        if (!isset($data['total_balken'])) {
            $data['total_balken'] = 0;
        }

        if (!isset($data['pallet']) || !is_array($data['pallet'])) {
            $data['total_volume'] = 0;
            $data['total_balken'] = 0;
            return $data;
        }

        foreach ($data['pallet'] as $index => &$pallet) {
            // Clean up unwanted fields
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
        // Redirect ke halaman Create ini sendiri untuk merefresh form dan menampilkan data terbaru
        $this->redirect(static::getUrl(['record' => $this->record->getKey()]));
    }
}