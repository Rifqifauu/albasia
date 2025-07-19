<?php

namespace App\Filament\Resources\KilnDryResource\Pages;
use App\Models\KilnDry;
use App\Filament\Resources\KilnDryResource;
use Filament\Resources\Pages\Page;

class KilnDryScan extends Page
{
    protected static string $resource = KilnDryResource::class;
    protected static string $view = 'filament.resources.kiln-dry-resource.pages.kiln-dry-scan';

    public KilnDry $record;

    #[Reactive]
    public ?string $lastScannedCode = null;

    public ?string $message = null;

    public function mount(KilnDry $record): void
    {
        $this->record = $record;
    }

    public function updatedLastScannedCode($code)
    {
        \Log::info('Barcode scanned: ' . $code);

        if (empty($code)) {
            return;
        }

        $this->lastScannedCode = null;
    }

    public function testScan($code = 'TEST123')
    {
        $this->lastScannedCode = $code;
        $this->updatedLastScannedCode($code);
    }

    public function resetMessage()
    {
        $this->message = null;
    }
}
