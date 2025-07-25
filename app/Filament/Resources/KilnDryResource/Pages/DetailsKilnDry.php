<?php

namespace App\Filament\Resources\KilnDryResource\Pages;

use App\Models\TallyLog;
use App\Models\TallyBalken;
use App\Models\KilnDry;
use App\Models\KubikasiBalken; // Add this line
use App\Models\KubikasiLog; // Add this line
use Filament\Resources\Pages\Page;

class DetailsKilnDry extends Page
{
    protected static string $resource = \App\Filament\Resources\KilnDryResource::class;

    protected static string $view = 'filament.resources.kiln-dry-resource.pages.details-kiln-dry';

    public $balkenData;
    public $logData;
    public $kilnDryRecord;
    public $rekapGradeLog; // Add this property
    public $rekapGradeBalken; // Add this property

    // Fetch data based on kndId
    public function mount($kndId)
    {
        $this->kilnDryRecord = KilnDry::findOrFail($kndId);
        $this->balkenData = TallyBalken::where('kiln_dries_id', $kndId)->get();
        $this->logData = TallyLog::where('kiln_dries_id', $kndId)->get();

        // Fetch rekap per grade data
        $this->rekapGradeBalken = KubikasiBalken::rekapPerGradeInKilnDry($kndId);
        $this->rekapGradeLog = KubikasiLog::rekapPerGradeInKilnDry($kndId);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Add any header actions you need
        ];
    }
}
