<?php

namespace App\Filament\Resources\KilnDryResource\Pages;

use App\Models\TallyLog;
use App\Models\TallyBalken;
use App\Models\KilnDry;

use App\Models\KubikasiBalken;
use App\Models\KubikasiLog;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class DetailsKilnDry extends Page
{
    protected static string $resource = \App\Filament\Resources\KilnDryResource::class;

    protected static string $view = 'filament.resources.kiln-dry-resource.pages.details-kiln-dry';

    public $balkenData;
    public $logData;
    public $kilnDryRecord;
    public $rekapGradeLog;
    public $rekapGradeBalken;

    public function mount($kndId)
    {
        $this->kilnDryRecord = KilnDry::findOrFail($kndId);
        $this->balkenData = TallyBalken::where('kiln_dries_id', $kndId)->get();
        $this->logData = TallyLog::where('kiln_dries_id', $kndId)->get();

        $this->rekapGradeBalken = KubikasiBalken::rekapPerGradeInKilnDry($kndId);
        $this->rekapGradeLog = KubikasiLog::rekapPerGradeInKilnDry($kndId);
    }
public function setStatus($id)
{
    TallyLog::where('kiln_dries_id', $id)->update(['is_stock' => false]);
    TallyBalken::where('kiln_dries_id', $id)->update(['is_stock' => false]);
}

    protected function getHeaderActions(): array
{
    return [
        Action::make('bongkarSekarang')
    ->label('Bongkar Sekarang')
    ->visible(fn () => is_null($this->kilnDryRecord->tanggal_bongkar))
    ->action(function () {
        $this->kilnDryRecord->tanggal_bongkar = now()->toDateString();
        $this->kilnDryRecord->save();

        $this->setStatus($this->kilnDryRecord->id); // Panggil function setStatus

        $this->dispatch('refresh');
    })
    ->after(function () {
        \Filament\Notifications\Notification::make()
            ->title('Sukses!')
            ->body('Tanggal bongkar berhasil diset, status stok di-update.')
            ->success()
            ->send();
    })
    ->requiresConfirmation()
    ->color('success')
    ->icon('heroicon-o-calendar-days'),
    ];
}

}
